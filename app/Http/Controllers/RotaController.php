<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Estoque;
use App\Models\Requisicao;
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

        // Busca requisições pendentes
        $requisicoesPendentes = Requisicao::whereDoesntHave('rotas')->get();

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
        ], [
            'requisicoes.required' => 'Você precisa selecionar pelo menos uma requisição para a carga!',
            'previsao_chegada.after_or_equal' => 'A data de chegada não pode ser anterior à data de saída.'
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

            // Captura o nome do estoque para o histórico
            $estoqueOrigem = Estoque::find($request->estoque_origem_id)->nome ?? 'Estoque Origem';

            // 2. Vincular na tabela pivô (rota_requisicao)
            $rota->requisicoes()->attach($request->requisicoes);

            // 3. Atualizar requisições e movimentar equipamentos
            foreach ($request->requisicoes as $id) {
                $req = Requisicao::find($id);
                if ($req) {
                    $req->situacao = 'Em Rota';
                    $req->save();

                    // Pega os IDs únicos dos equipamentos que já foram separados para esta requisição
                    $equipamentosIds = \App\Models\Movimentacao::where('requisicao_id', $req->id)
                        ->pluck('equipamento_id')
                        ->unique();

                    $equipamentos = \App\Models\Equipamento::whereIn('id', $equipamentosIds)->get();

                    foreach ($equipamentos as $equip) {
                        // Atualiza status do equipamento na listagem
                        $equip->update(['situacao' => 'Em Rota']);

                        // Registra a saída do estoque para o caminhão/veículo
                        \App\Models\Movimentacao::create([
                            'equipamento_id'    => $equip->id,
                            'requisicao_id'     => $req->id,
                            'tipo'              => 'Aluguel',
                            'situacao'          => 'Em Rota',
                            'origem'            => $estoqueOrigem,
                            'destino'           => "Veículo Rota #{$rota->id}",
                            'data_movimentacao' => now(),
                            'observacao'        => "Despachado na Rota #{$rota->id}"
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('rotas.index')->with('success', 'Rota despachada com sucesso e equipamentos em trânsito!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro técnico: ' . $e->getMessage());
        }
    }

    // 5 Detalhes de uma rota esxpessifica
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

        if ($request->has('status')) {

            try {
                DB::beginTransaction();

                $rota->update([
                    'status' => $request->status
                ]);

                // Se a rota foi concluída, entregamos os equipamentos
                if ($request->status == 'Entregue') {
                    foreach ($rota->requisicoes as $req) {
                        $req->update(['situacao' => 'Entregue']);

                        // Busca novamente os equipamentos desta requisição
                        $equipamentosIds = \App\Models\Movimentacao::where('requisicao_id', $req->id)
                            ->pluck('equipamento_id')
                            ->unique();

                        $equipamentos = \App\Models\Equipamento::whereIn('id', $equipamentosIds)->get();

                        foreach ($equipamentos as $equip) {
                            // Atualiza o sub-status do equipamento para finalizado
                            $equip->update(['situacao' => 'No Cliente']);

                            // Registra a chegada no destino final
                            \App\Models\Movimentacao::create([
                                'equipamento_id'    => $equip->id,
                                'requisicao_id'     => $req->id,
                                'tipo'              => 'Aluguel',
                                'situacao'          => 'No Cliente',
                                'origem'            => "Veículo Rota #{$rota->id}",
                                'destino'           => $req->cliente->nome ?? 'Cliente',
                                'data_movimentacao' => now(),
                                'observacao'        => "Entregue via Rota #{$rota->id}"
                            ]);
                        }
                    }
                }

                DB::commit();
                return redirect()->route('rotas.index')->with('success', 'Rota atualizada com sucesso!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Erro ao finalizar rota: ' . $e->getMessage());
            }
        }

        return redirect()->route('rotas.index');
    }

    public function imprimir($id)
    {
        $rota = Rota::with(['motorista', 'veiculo', 'requisicoes.cliente', 'requisicoes.catalogo'])->findOrFail($id);
        return view('rotas.imprimir', compact('rota'));
    }
}
