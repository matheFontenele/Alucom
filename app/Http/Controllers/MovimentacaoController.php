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
     * Lista o histórico de movimentações com paginação.
     */
    public function index()
    {
        $movimentacoes = Movimentacao::with(['equipamento'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    /**
     * Exibe o formulário para nova movimentação.
     */
    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

    /**
     * Registra a movimentação e atualiza o estado do equipamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id' => 'required|exists:equipamentos,id',
            'tipo' => 'required',
            'situacao' => 'required',
            'origem' => 'required',
            'destino' => 'required',
            'data_movimentacao' => 'required',
        ]);

        return DB::transaction(function () use ($request) {
            $equipamento = Equipamento::findOrFail($request->equipamento_id);
            $tipo = $request->tipo;
            $situacao = $request->situacao;

            // Regra para Disponível: Volta para o estoque e limpa cliente
            if ($tipo === 'Disponível') {
                $equipamento->status = 'Disponível';
                $equipamento->cliente_id = null;
                $estoque = Estoque::where('nome', $request->destino)->first();
                $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
            }

            // Regra para Devolução
            elseif ($tipo === 'Devolução') {
                $equipamento->status = 'Devolução';
                if ($situacao !== 'Aguardando Coleta') {
                    $equipamento->cliente_id = null;
                    $estoque = Estoque::where('nome', $request->destino)->first();
                    $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                }
            }

            // Regra para Aluguel
            elseif ($tipo === 'Aluguel') {
                $equipamento->status = 'Alugado';
                if ($situacao !== 'Aguardando Rota') {
                    $equipamento->estoque_id = null;
                    $cliente = Clientes::where('nome', $request->destino)->first();
                    $equipamento->cliente_id = $cliente->id ?? $equipamento->cliente_id;
                }
            }

            $equipamento->situacao = $situacao;
            $equipamento->save();

            // Salva o histórico (Usando only para evitar colunas inexistentes no banco)
            Movimentacao::create($request->only([
                'equipamento_id',
                'tipo',
                'situacao',
                'origem',
                'destino',
                'data_movimentacao',
                'observacao'
            ]));

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada!');
        });
    }

    /**
     * Metodo para abrir pagina de edição
     */
    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);

        // Precisamos carregar os dados para os selects do formulário
        $equipamentos = Equipamento::all();
        $clientes = Clientes::all();
        $estoques = Estoque::all();

        return view('movimentacoes.edit', compact('movimentacao', 'equipamentos', 'clientes', 'estoques'));
    }

    // 2. Método para salvar as alterações
    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao' => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            // Atualiza o histórico da movimentação
            $movimentacao->update($request->only(['situacao', 'observacao']));

            // Aplica a regra de negócio no Equipamento (No Cliente vs Em Rota)
            // Se for Devolução e mudar para 'Em Rota', o equipamento sai do cliente
            if ($movimentacao->tipo === 'Devolução' && $request->situacao === 'Em Rota') {
                $equipamento->cliente_id = null;
                $estoque = Estoque::where('nome', $movimentacao->destino)->first();
                $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
            }

            // Se for Aluguel e mudar para 'Em Rota', o equipamento sai do estoque
            if ($movimentacao->tipo === 'Aluguel' && $request->situacao === 'Em Rota') {
                $equipamento->estoque_id = null;
                $cliente = Clientes::where('nome', $movimentacao->destino)->first();
                $equipamento->cliente_id = $cliente->id ?? $equipamento->cliente_id;
            }

            $equipamento->situacao = $request->situacao;
            $equipamento->save();

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação atualizada com sucesso!');
        });
    }
    /**
     * Remove um registro do histórico de movimentações.
     */
    public function destroy($id)
    {
        try {
            $movimentacao = Movimentacao::findOrFail($id);
            $movimentacao->delete();

            return redirect()->back()->with('success', 'Histórico de movimentação removido!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível excluir o registro.');
        }
    }
}
