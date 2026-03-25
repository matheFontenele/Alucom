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
    public function index()
    {
        $movimentacoes = Movimentacao::with(['equipamento'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();

        $clientes = Clientes::with('parent')->orderBy('nome')->get();

        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

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

            // Regra para Disponível
            if ($tipo === 'Disponível') {
                $equipamento->status = 'Disponivel';
                $equipamento->cliente_id = null;
                $estoque = Estoque::where('nome', $request->destino)->first();
                $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
            }
            // Regra para Devolução
            elseif ($tipo === 'Devolução') {
                $equipamento->status = 'Devolução';
                if ($situacao === 'Recebido') {
                    $equipamento->status = 'Disponivel';
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

    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacoes.edit', compact('movimentacao'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao' => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            $movimentacao->update($request->only(['situacao', 'observacao']));

            // Se marcar como Disponível ou Recebido na edição, libera o equipamento
            if ($request->situacao === 'Em Estoque' || $request->situacao === 'Recebido') {
                $equipamento->status = 'Disponivel';
                $equipamento->cliente_id = null;
                $estoque = Estoque::where('nome', $movimentacao->destino)->first();
                $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
            }

            $equipamento->situacao = $request->situacao;
            $equipamento->save();

            return redirect()->route('movimentacoes.index')->with('success', 'Situação atualizada!');
        });
    }

    public function destroy($id)
    {
        Movimentacao::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Registro removido!');
    }
}
