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
        // 1. Ajuste na Validação: Removido 'required' de campos que podem ser nulos no banco
        $data = $request->validate([
            'tipo'        => 'required|in:equipamento,insumo',
            'catalogo_id' => 'required|exists:catalogo,id',
            'estoque_id'  => 'required|exists:estoques,id',
            'tombo'       => 'nullable|string|max:255',
            'serial'      => 'nullable|string|max:255',
            'quantidade'  => 'nullable|integer|min:1',
            'status'      => 'nullable|string',
        ]);

        $itemCatalogo = Catalogo::findOrFail($data['catalogo_id']);
        $statusFinal = $request->status ?? 'Disponivel';

        try {
            if ($request->tipo === 'insumo') {
                $qtd = $request->input('quantidade', 1);

                for ($i = 0; $i < $qtd; $i++) {
                    Equipamento::create([
                        'tipo'              => 'insumo',
                        'catalogo_id'       => $itemCatalogo->id,
                        'categoria_id'      => $itemCatalogo->categoria_id, // ADICIONADO: Garante que o ID da categoria seja salvo
                        'nome'              => $itemCatalogo->nome,
                        'estoque_id'        => $data['estoque_id'],
                        'status'            => $statusFinal,
                        'cor'               => $itemCatalogo->cor,
                        'data_movimentacao' => now(),
                    ]);
                }
                $mensagem = "{$qtd} unidades de \"{$itemCatalogo->nome}\" adicionadas!";
            } else {
                Equipamento::create([
                    'tipo'              => 'equipamento',
                    'catalogo_id'       => $itemCatalogo->id,
                    'categoria_id'      => $itemCatalogo->categoria_id, // ADICIONADO: Garante que o ID da categoria seja salvo
                    'nome'              => $itemCatalogo->nome,
                    'tombo'             => $data['tombo'],
                    'serial'            => $data['serial'],
                    'estoque_id'        => $data['estoque_id'],
                    'status'            => $statusFinal,
                    'cor'               => $itemCatalogo->cor,
                    'data_movimentacao' => now(),
                ]);
                $mensagem = "Equipamento \"{$itemCatalogo->nome}\" cadastrado!";
            }

            return redirect()->back()->with('success', $mensagem);
        } catch (\Exception $e) {
            // Oculta detalhes técnicos para o usuário mas mantém no log para você
            return redirect()->back()
                ->withErrors(['erro' => 'Erro ao salvar: Verifique se o Tombo/Serial já existe ou se faltam campos obrigatórios.'])
                ->withInput();
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
        $data = $request->validate([
            'tipo'         => 'required|in:equipamento,insumo',
            'nome'         => 'required|string|max:255',
            'tombo'        => $request->tipo === 'equipamento'
                ? 'required|string|unique:equipamentos,tombo,' . $equipamento->id
                : 'nullable',
            'serial'       => 'nullable|string|unique:equipamentos,serial,' . $equipamento->id,
            'status'       => 'required|string',
            'cor'          => 'nullable|string',
            'observacoes'  => 'nullable|string',
            'estoque_id'   => 'required|exists:estoques,id'
        ]);

        $equipamento->update($data);

        return redirect()->route('estoques.show', $equipamento->estoque_id)
            ->with('success', 'Cadastro atualizado com sucesso!');
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
