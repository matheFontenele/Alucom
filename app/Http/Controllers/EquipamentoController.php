<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Categoria;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Buscamos os equipamentos e também as categorias para o formulário
        $equipamentos = Equipamento::with(['categoria', 'subcategoria'])->latest()->get();
        $categorias = Categoria::with('subcategorias')->get();

        return view('equipamentos.index', compact('equipamentos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = \App\Models\Categoria::orderBy('nome')->get();
        $clientes = \App\Models\Clientes::whereNull('parent_id')->orderBy('nome')->get();
        $estoques = \App\Models\Estoque::orderBy('nome')->get();

        return view('equipamentos.create', compact('categorias', 'clientes', 'estoques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:255',
            'tombo'          => 'required|string|max:5|unique:equipamentos,tombo',
            'serial'         => 'nullable|string|unique:equipamentos,serial',
            'categoria_id'   => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'cliente_id'     => 'nullable|exists:clientes,id',
            'estoque_id'     => 'nullable|exists:estoques,id',
            'status'         => 'required|in:Alugado,Devolução,Disponivel,Manutenção,Reservado',
            'situacao'       => 'nullable|string',
        ]);

        \App\Models\Equipamento::create($data);

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento cadastrado!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipamento $equipamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipamento $equipamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        //
    }
}
