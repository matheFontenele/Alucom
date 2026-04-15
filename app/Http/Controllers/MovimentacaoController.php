<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Equipamento;
use App\Models\Clientes;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentacaoController extends Controller
{
    /**
     * Lista o histórico de movimentações.
     */
    public function index()
    {
        // Carrega também a requisição caso exista, para exibir na listagem
        $movimentacoes = Movimentacao::with(['equipamento', 'requisicao'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    /**
     * Formulário para criação de movimentação manual.
     */
    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

    /**
     * Registra uma nova movimentação e atualiza o estado do equipamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id'    => 'required|exists:equipamentos,id',
            'tipo'              => 'required|string', // Status Pai: Alugado, Devolução, Manutenção, etc.
            'situacao'          => 'required|string', // Substatus: No Cliente, Em Estoque, etc.
            'origem'            => 'required|string',
            'destino'           => 'required|string',
            'data_movimentacao' => 'required|date',
            'requisicao_id'     => 'nullable|exists:requisicoes,id',
            'observacao'        => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $equipamento = Equipamento::findOrFail($request->equipamento_id);

            // 1. Atualiza o estado atual do Equipamento baseado na movimentação
            $equipamento->status = $request->tipo;
            $equipamento->situacao = $request->situacao;

            // 2. Lógica de atribuição de Posse (Estoque vs Cliente)
            switch ($request->tipo) {
                case 'Liberado':
                case 'Manutenção':
                case 'Devolução':
                    // Equipamento volta ou permanece na empresa
                    if (str_contains($request->situacao, 'Estoque') || $request->situacao === 'Liberado') {
                        $equipamento->cliente_id = null;
                        $estoque = Estoque::where('nome', $request->destino)->first();
                        $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                    }
                    break;

                case 'Alugado':
                case 'Reservado':
                    // Equipamento vai ou permanece com cliente
                    if ($request->situacao === 'No Cliente') {
                        $equipamento->estoque_id = null;
                        $cliente = Clientes::where('nome', $request->destino)->first();
                        $equipamento->cliente_id = $cliente->id ?? $equipamento->cliente_id;
                    }
                    break;
            }

            $equipamento->save();

            // 3. Cria o registro no histórico de movimentações
            Movimentacao::create($request->only([
                'equipamento_id',
                'requisicao_id',
                'tipo',
                'situacao',
                'origem',
                'destino',
                'data_movimentacao',
                'observacao'
            ]));

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
        });
    }

    /**
     * Tela de edição (Geralmente para ajuste de observações ou substatus).
     */
    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacoes.edit', compact('movimentacao'));
    }

    /**
     * Atualiza o registro e sincroniza o estado do equipamento.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao'   => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            // Atualiza o histórico
            $movimentacao->update($request->only(['situacao', 'observacao']));

            // Sincroniza o substatus no equipamento
            $equipamento->situacao = $request->situacao;

            // Se na edição mudar para um estado de posse em estoque, limpa cliente
            if (in_array($request->situacao, ['Em Estoque', 'Recebido', 'Liberado'])) {
                $equipamento->status = 'Liberado';
                $equipamento->cliente_id = null;
                $estoque = Estoque::where('nome', $movimentacao->destino)->first();
                $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
            }

            $equipamento->save();

            return redirect()->route('movimentacoes.index')->with('success', 'Registro e estado do equipamento atualizados!');
        });
    }

    /**
     * Remove um registro de movimentação.
     */
    public function destroy($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->delete();

        return redirect()->back()->with('success', 'Registro de movimentação removido!');
    }
}