<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Estoque;
use App\Models\Requisicao;
use App\Models\Movimentacao;
use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RotaController extends Controller
{
    public function index()
    {
        $rotas = Rota::with(['motorista', 'veiculo', 'requisicoes'])
            ->orderBy('data_saida', 'desc')
            ->get();

        return view('rotas.index', compact('rotas'));
    }

    public function create()
    {
        $motoristas = User::where('funcao', 'Motorista')->get();
        $veiculos = Veiculo::all();
        $estoques = Estoque::all();

        // Busca requisições que estão com situação 'Finalizada' (já separadas) mas sem rota
        $requisicoesPendentes = Requisicao::where('situacao', 'Finalizada')
            ->whereDoesntHave('rotas')
            ->get();

        return view('rotas.create', compact('motoristas', 'veiculos', 'estoques', 'requisicoesPendentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'estoque_origem_id' => 'required|exists:estoques,id',
            'cidade_destino' => 'required|string',
            'estado_destino' => 'required|string|max:2',
            'data_saida' => 'required|date',
            'previsao_chegada' => 'required|date|after_or_equal:data_saida',
            'requisicoes' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Criar a Rota
            $rota = Rota::create([
                'user_id' => $request->user_id,
                'veiculo_id' => $request->veiculo_id,
                'estoque_origem_id' => $request->estoque_origem_id,
                'cidade_destino' => $request->cidade_destino,
                'estado_destino' => $request->estado_destino,
                'data_saida' => $request->data_saida,
                'previsao_chegada' => $request->previsao_chegada,
                'status' => 'Em Rota',
                'observacoes' => $request->observacoes,
            ]);

            $veiculo = Veiculo::find($request->veiculo_id);
            $placa = $veiculo ? $veiculo->placa : 'Veículo';

            // 2. Vincular na tabela pivô
            $rota->requisicoes()->attach($request->requisicoes);

            // 3. Atualizar Movimentações e Equipamentos para "Em Rota"
            foreach ($request->requisicoes as $id) {
                $req = Requisicao::find($id);
                if ($req) {
                    $req->update(['situacao' => 'Em Rota']);

                    // Busca as movimentações que foram criadas no RequisicaoController (Sub-status: Separado)
                    $movimentacoes = Movimentacao::where('requisicao_id', $req->id)
                        ->where('situacao', 'Separado')
                        ->get();

                    foreach ($movimentacoes as $mov) {
                        // Atualiza a movimentação existente para "Em Rota"
                        $mov->update([
                            'situacao' => 'Em Rota',
                            'destino'  => "Veículo: {$placa} (Rota #{$rota->id})",
                            'observacao' => $mov->observacao . " | Despachado na Rota #{$rota->id}"
                        ]);

                        // Atualiza o sub-status no cadastro do Equipamento
                        if ($mov->equipamento_id) {
                            Equipamento::where('id', $mov->equipamento_id)->update([
                                'situacao' => 'Em Rota'
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('rotas.index')->with('success', 'Rota criada e itens atualizados para "Em Rota"!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro técnico: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $rota = Rota::with([
            'motorista',
            'veiculo',
            'requisicoes.cliente',
            'requisicoes.catalogo'
        ])->findOrFail($id);

        return view('rotas.show', compact('rota'));
    }

    public function update(Request $request, $id)
    {
        $rota = Rota::with(['requisicoes.cliente'])->findOrFail($id);

        if ($request->status == 'Entregue') {
            try {
                DB::beginTransaction();

                $rota->update(['status' => 'Entregue']);

                foreach ($rota->requisicoes as $req) {
                    $req->update(['situacao' => 'Concluida']);

                    // Localiza as movimentações que estavam em rota para esta requisição
                    $movimentacoes = Movimentacao::where('requisicao_id', $req->id)
                        ->where('situacao', 'Em Rota')
                        ->get();

                    foreach ($movimentacoes as $mov) {
                        // Finaliza a movimentação
                        $mov->update([
                            'situacao' => 'Concluida',
                            'destino'  => $req->cliente->nome ?? 'Cliente Final',
                            'data_movimentacao' => now()
                        ]);

                        // Finaliza o sub-status do Equipamento
                        if ($mov->equipamento_id) {
                            Equipamento::where('id', $mov->equipamento_id)->update([
                                'situacao' => 'Concluida'
                            ]);
                        }
                    }
                }

                DB::commit();
                return redirect()->route('rotas.index')->with('success', 'Rota finalizada e equipamentos entregues!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Erro ao finalizar rota: ' . $e->getMessage());
            }
        }

        // Caso seja apenas uma atualização de status simples (ex: Cancelada)
        $rota->update(['status' => $request->status]);
        return redirect()->route('rotas.index');
    }

    public function imprimir($id)
    {
        $rota = Rota::with(['motorista', 'veiculo', 'requisicoes.cliente', 'requisicoes.catalogo'])->findOrFail($id);
        return view('rotas.imprimir', compact('rota'));
    }
}