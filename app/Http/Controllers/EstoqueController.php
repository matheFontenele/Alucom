<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Catalogo;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // O withCount('equipamentos') cria automaticamente a propriedade equipamentos_count
        $estoques = Estoque::withCount('equipamentos')->get();

        return view('estoques.index', compact('estoques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('estoques.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $estoque = Estoque::findOrFail($id);

        // 1. Buscamos todos os modelos do catálogo para os Modais
        $modelosCatalogo = Catalogo::orderBy('nome')->get();

        $query = $estoque->equipamentos();

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 2. Agrupamento para a contagem na tela principal do estoque
        $equipamentosAgrupados = $query->select('nome', 'status', \DB::raw('count(*) as total'))
            ->groupBy('nome', 'status')
            ->get();

        // Passamos $modelosCatalogo para a View
        return view('estoques.show', compact('estoque', 'equipamentosAgrupados', 'modelosCatalogo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estoque $estoque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estoque $estoque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estoque $estoque)
    {
        //
    }

    public function detalhesItem(Request $request, $estoqueId, $nome)
    {
        $estoque = \App\Models\Estoque::findOrFail($estoqueId);

        // Inicia a query filtrando pelo estoque e nome do grupo
        $query = \App\Models\Equipamento::where('estoque_id', $estoqueId)
            ->where('nome', $nome);

        // Aplica o filtro de busca (se houver)
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
