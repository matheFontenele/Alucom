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
            'tipo'           => 'required|in:equipamento,insumo',
            'nome'           => 'required|string|max:255',
            // Tombo só é obrigatório se for equipamento
            'tombo'          => $request->tipo === 'equipamento' ? 'required|string|max:10|unique:equipamentos,tombo' : 'nullable|string',
            'serial'         => 'nullable|string|unique:equipamentos,serial',
            'categoria_id'   => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'cliente_id'     => 'nullable|exists:clientes,id',
            'estoque_id'     => 'nullable|exists:estoques,id',
            'status'         => 'required|in:Alugado,Devolução,Disponivel,Manutenção,Reservado',
            'situacao'       => 'nullable|string',
            'cor'            => 'nullable|string', // Para toners
            'observacoes'    => 'nullable|string', // Para ambos
        ]);

        Equipamento::create($data);

        // Redireciona de volta para o estoque específico se houver, ou para o index
        if ($request->estoque_id) {
            return redirect()->route('estoques.show', $request->estoque_id)->with('success', 'Item cadastrado no estoque!');
        }

        return redirect()->route('equipamentos.index')->with('success', 'Item cadastrado com sucesso!');
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
        // Carregamos as mesmas dependências do Create, mas para o Equipamento específico
        $categorias = \App\Models\Categoria::orderBy('nome')->get();
        $clientes = \App\Models\Clientes::whereNull('parent_id')->orderBy('nome')->get();
        $estoques = \App\Models\Estoque::orderBy('nome')->get();

        return view('equipamentos.edit', compact('equipamento', 'categorias', 'clientes', 'estoques'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        $data = $request->validate([
            'tipo'           => 'required|in:equipamento,insumo',
            'nome'           => 'required|string|max:255',
            'tombo'          => $request->tipo === 'equipamento'
                ? 'required|string|max:10|unique:equipamentos,tombo,' . $equipamento->id
                : 'nullable|string',
            'serial'         => 'nullable|string|unique:equipamentos,serial,' . $equipamento->id,
            'categoria_id'   => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'status'         => 'required|string',
            'cor'            => 'nullable|string',
            'observacoes'    => 'nullable|string',
        ]);

        $equipamento->update($data);

        return redirect()->route('equipamentos.index')->with('success', 'Cadastro atualizado!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return redirect()->route('equipamentos.index')->with('success', 'Equipamento excluído!');
    }
}
