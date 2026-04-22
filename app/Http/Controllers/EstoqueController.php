<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Catalogo;
use App\Models\Equipamento;
use App\Models\BiddingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    public function index()
    {
        $estoques = Estoque::withCount('equipamentos')->get();
        return view('estoques.index', compact('estoques'));
    }

    public function create()
    {
        return view('estoques.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
        ]);

        Estoque::create($data);
        return redirect()->route('estoques.index')->with('success', 'Local de estoque criado com sucesso!');
    }

    public function show($id, Request $request)
    {
        $estoque = Estoque::findOrFail($id);
        $modelosCatalogo = Catalogo::with('categoria')->orderBy('nome')->get();

        $query = $estoque->equipamentos();

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Agrupamento para visão geral do estoque
        $equipamentosAgrupados = $query->select('nome', 'status', DB::raw('count(*) as total'))
            ->groupBy('nome', 'status')
            ->get();

        return view('estoques.show', compact('estoque', 'equipamentosAgrupados', 'modelosCatalogo'));
    }

    /**
     * NOVA FUNCIONALIDADE: Sugestão de Match de Estoque para Licitação
     * Busca equipamentos cujas especificações batem com as palavras-chave do edital.
     */
    public function suggestForBidding(Request $request)
    {
        $description = $request->get('description'); // Ex: "Notebook I5 8GB SSD"

        // Limpeza e extração de palavras-chave (ignora palavras curtas)
        $keywords = array_filter(explode(' ', strtolower($description)), function ($word) {
            return strlen($word) > 2;
        });

        // Busca no Catálogo de Modelos que batem com as palavras-chave
        $query = Catalogo::query();
        foreach ($keywords as $word) {
            $query->where('especificacoes', 'like', "%{$word}%")
                ->orWhere('nome', 'like', "%{$word}%");
        }

        $sugestoes = $query->limit(5)->get();

        return response()->json($sugestoes);
    }

    /**
     * Confirma qual modelo de catálogo será usado para aquele item da licitação.
     */
    public function confirmModel(Request $request)
    {
        $request->validate([
            'bidding_item_id' => 'required|exists:bidding_items,id',
            'catalogo_id'     => 'required|exists:catalogos,id',
        ]);

        $item = BiddingItem::findOrFail($request->bidding_item_id);
        $modelo = Catalogo::findOrFail($request->catalogo_id);

        $item->update([
            'confirmed_model' => $modelo->nome,
            'catalogo_id'     => $modelo->id // Certifique-se de ter essa coluna na sua tabela de itens
        ]);

        return response()->json(['success' => true, 'message' => 'Modelo homologado com sucesso!']);
    }

    public function detalhesItem(Request $request, $estoqueId, $nome)
    {
        $estoque = Estoque::findOrFail($estoqueId);
        $query = Equipamento::where('estoque_id', $estoqueId)->where('nome', $nome);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial', 'like', "%{$search}%")
                    ->orWhere('tombo', 'like', "%{$search}%");
            });
        }

        $itens = $query->get();
        return view('estoques.detalhes-item', compact('estoque', 'itens', 'nome'));
    }
}
