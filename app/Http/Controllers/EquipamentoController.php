<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Categoria;
use App\Models\Catalogo;
use App\Models\Estoque;
use App\Models\Clientes;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    /**
     * Lista todos os equipamentos e categorias.
     */
    public function index()
    {
        $equipamentos = Equipamento::with(['catalogo.categoria'])->latest()->get();
        $categorias = Categoria::with('subcategorias')->get();

        return view('equipamentos.index', compact('equipamentos', 'categorias'));
    }

    /**
     * Formulário de criação (Caso use página separada).
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        $clientes = Clientes::whereNull('parent_id')->orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('equipamentos.create', compact('categorias', 'clientes', 'estoques'));
    }

    /**
     * Salva o equipamento ou insumo vindo dos modais.
     */
    public function store(Request $request)
    {
        // 1. REGRAS DE VALIDAÇÃO RESTRITAS
        $regras = [
            'tipo'            => 'required|in:equipamento,insumo',
            'catalogo_id'     => 'required|exists:catalogo,id',
            'status'          => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'situacao'        => 'nullable|in:Novo,Revisado,Sucata',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'quantidade'      => 'nullable|integer|min:1',

            // Tombo: Obrigatório se for equipamento, exatamente 5 dígitos e único
            'tombo' => $request->tipo === 'equipamento'
                ? 'required|digits:5|unique:equipamentos,tombo'
                : 'nullable',

            // Serial: Obrigatório se for equipamento e único
            'serial' => $request->tipo === 'equipamento'
                ? 'required|string|unique:equipamentos,serial'
                : 'nullable',
        ];

        // 2. LÓGICA DE LOCAL (CLIENTE VS ESTOQUE)
        if (in_array($request->status, ['Alugado', 'Reservado'])) {
            $regras['cliente_id'] = 'required|exists:clientes,id';
            $estoque_id = null; // Tira do estoque
            $cliente_id = $request->cliente_id;
        } else {
            // Liberado, Manutenção, Devolução
            $regras['estoque_id'] = 'required|exists:estoques,id';
            $cliente_id = null; // Tira do cliente
            $estoque_id = $request->estoque_id;
        }

        $data = $request->validate($regras);
        $itemCatalogo = Catalogo::findOrFail($data['catalogo_id']);

        try {
            if ($request->tipo === 'insumo') {
                $qtd = $request->input('quantidade', 1);
                for ($i = 0; $i < $qtd; $i++) {
                    Equipamento::create([
                        'tipo'              => 'insumo',
                        'catalogo_id'       => $itemCatalogo->id,
                        'categoria_id'      => $itemCatalogo->categoria_id,
                        'subcategoria_id'   => $request->subcategoria_id,
                        'nome'              => $itemCatalogo->nome,
                        'status'            => $request->status,
                        'situacao'          => $request->situacao,
                        'estoque_id'        => $estoque_id,
                        'cliente_id'        => $cliente_id,
                        'cor'               => $itemCatalogo->cor,
                        'data_movimentacao' => now(),
                    ]);
                }
                $mensagem = "{$qtd} unidades de \"{$itemCatalogo->nome}\" adicionadas!";
            } else {
                Equipamento::create([
                    'tipo'              => 'equipamento',
                    'catalogo_id'       => $itemCatalogo->id,
                    'categoria_id'      => $itemCatalogo->categoria_id,
                    'subcategoria_id'   => $request->subcategoria_id,
                    'nome'              => $itemCatalogo->nome,
                    'tombo'             => $data['tombo'],
                    'serial'            => $data['serial'],
                    'status'            => $request->status,
                    'situacao'          => $request->situacao,
                    'estoque_id'        => $estoque_id,
                    'cliente_id'        => $cliente_id,
                    'cor'               => $itemCatalogo->cor,
                    'data_movimentacao' => now(),
                ]);
                $mensagem = "Equipamento cadastrado com sucesso!";
            }

            return redirect()->back()->with('success', $mensagem);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erro' => 'Erro ao salvar. Verifique os dados.'])->withInput();
        }
    }

    /**
     * Exibe os detalhes de um equipamento específico.
     */
    public function show($id)
    {
        // Busca o equipamento e trás junto as informações do catálogo, estoque e categoria
        $equipamento = Equipamento::with(['catalogo', 'estoque', 'categoria'])->findOrFail($id);

        // Retorna a view de detalhes passando o equipamento
        return view('equipamentos.show', compact('equipamento'));
    }

    /**
     * Formulário de edição.
     */
    public function edit(Equipamento $equipamento)
    {
        $categorias = Categoria::orderBy('nome')->get();
        $clientes   = Clientes::whereNull('parent_id')->orderBy('nome')->get();
        $estoques   = Estoque::orderBy('nome')->get();

        return view('equipamentos.edit', compact('equipamento', 'categorias', 'clientes', 'estoques'));
    }

    /**
     * Atualiza o cadastro de um item.
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        $regras = [
            'status'          => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'situacao'        => 'nullable|in:Novo,Revisado,Sucata',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'tombo' => $equipamento->tipo === 'equipamento'
                ? 'required|digits:5|unique:equipamentos,tombo,' . $equipamento->id
                : 'nullable',
            'serial' => $equipamento->tipo === 'equipamento'
                ? 'required|string|unique:equipamentos,serial,' . $equipamento->id
                : 'nullable',
        ];

        // Aplica a mesma lógica de local na edição
        if (in_array($request->status, ['Alugado', 'Reservado'])) {
            $regras['cliente_id'] = 'required|exists:clientes,id';
            $data['estoque_id'] = null;
            $data['cliente_id'] = $request->cliente_id;
        } else {
            $regras['estoque_id'] = 'required|exists:estoques,id';
            $data['cliente_id'] = null;
            $data['estoque_id'] = $request->estoque_id;
        }

        $data = array_merge($request->validate($regras), $data);

        $equipamento->update($data);

        return redirect()->back()->with('success', 'Cadastro atualizado com sucesso!');
    }

    /**
     * Remove um item do sistema.
     */
    public function destroy(Equipamento $equipamento)
    {
        $estoqueId = $equipamento->estoque_id;
        $equipamento->delete();

        return redirect()->back()->with('success', 'Item removido do estoque!');
    }
}
