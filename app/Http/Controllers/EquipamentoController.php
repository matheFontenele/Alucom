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
            'tipo'        => 'required|in:equipamento,insumo',
            'catalogo_id' => 'required|exists:catalogo,id', // Agora validamos o ID do catálogo
            'estoque_id'  => 'required|exists:estoques,id',
            'status'      => 'required',
            'tombo'       => 'nullable|string',
            'serial'      => 'nullable|string',
            'quantidade'  => 'nullable|integer|min:1',
        ]);

        // Buscamos as specs do catálogo para não precisar digitar de novo
        $itemCatalogo = \App\Models\Catalogo::find($data['catalogo_id']);

        if ($request->tipo === 'insumo') {
            $qtd = $request->input('quantidade', 1);

            for ($i = 0; $i < $qtd; $i++) {
                \App\Models\Equipamento::create([
                    'tipo'        => 'insumo',
                    'catalogo_id' => $itemCatalogo->id,
                    'nome'        => $itemCatalogo->nome, // Mantemos o nome para redundância/facilidade
                    'estoque_id'  => $data['estoque_id'],
                    'status'      => $data['status'] ?? 'Disponível',
                    'cor'         => $itemCatalogo->cor, // Puxa automático do catálogo
                    'data_movimentacao' => now(),
                ]);
            }
            $mensagem = "{$qtd} unidades de {$itemCatalogo->nome} adicionadas!";
        } else {
            \App\Models\Equipamento::create([
                'tipo'        => 'equipamento',
                'catalogo_id' => $itemCatalogo->id,
                'nome'        => $itemCatalogo->nome,
                'tombo'       => $data['tombo'],
                'serial'      => $data['serial'],
                'estoque_id'  => $data['estoque_id'],
                'status'      => $data['status'] ?? 'Disponível',
                'cor'         => $itemCatalogo->cor,
                'data_movimentacao' => now(),
            ]);
            $mensagem = "Equipamento {$itemCatalogo->nome} cadastrado!";
        }

        return redirect()->back()->with('success', $mensagem);
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
