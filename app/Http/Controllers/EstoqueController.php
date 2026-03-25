<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Catalogo;
use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    /**
     * Exibe a lista de locais de estoque.
     */
    public function index()
    {
        // Carrega os estoques contando quantos equipamentos existem em cada um
        $estoques = Estoque::withCount('equipamentos')->get();

        return view('estoques.index', compact('estoques'));
    }

    /**
     * Formulário de criação de local de estoque.
     */
    public function create()
    {
        return view('estoques.create');
    }

    /**
     * Salva um novo local de estoque.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
        ]);

        Estoque::create($data);

        return redirect()->route('estoques.index')->with('success', 'Local de estoque criado com sucesso!');
    }

    /**
     * Exibe os itens dentro de um estoque específico.
     */
    public function show($id, Request $request)
    {
        $estoque = Estoque::findOrFail($id);

        // 1. Buscamos todos os modelos do catálogo COM a categoria carregada.
        $modelosCatalogo = Catalogo::with('categoria')->orderBy('nome')->get();

        // 2. Iniciamos a query de equipamentos deste estoque
        $query = $estoque->equipamentos();

        // Filtro de busca por nome (do equipamento)
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Agrupamento para a tabela principal (agrupa itens iguais com status iguais)
        $equipamentosAgrupados = $query->select('nome', 'status', DB::raw('count(*) as total'))
            ->groupBy('nome', 'status')
            ->get();

        return view('estoques.show', compact('estoque', 'equipamentosAgrupados', 'modelosCatalogo'));
    }

    /**
     * Exibe os detalhes (seriais e tombos) de um grupo de itens específicos.
     */
    public function detalhesItem(Request $request, $estoqueId, $nome)
    {
        $estoque = Estoque::findOrFail($estoqueId);

        // Busca os itens individuais (com serial/tombo) baseados no nome do grupo
        $query = Equipamento::where('estoque_id', $estoqueId)
            ->where('nome', $nome);

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

    // Métodos edit, update e destroy podem ser implementados conforme sua necessidade
    public function edit(Estoque $estoque) { /* ... */ }
    public function update(Request $request, Estoque $estoque) { /* ... */ }
    public function destroy(Estoque $estoque) { /* ... */ }
}