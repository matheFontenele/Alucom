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
        // CORREÇÃO: motorista (com um 'a' apenas)
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

            // 2. Vincular na tabela pivô (rota_requisicao)
            $rota->requisicoes()->attach($request->requisicoes);

            // 3. Atualizar cada requisição individualmente para evitar bloqueio de fillable
            foreach ($request->requisicoes as $id) {
                $req = Requisicao::find($id);
                if ($req) {
                    // Se no seu banco o campo for 'situacao', mantenha assim. 
                    // Se for 'status', troque abaixo:
                    $req->situacao = 'Em Rota'; 
                    $req->save();
                }
            }

            DB::commit();
            return redirect()->route('rotas.index')->with('success', 'Rota despachada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro técnico: ' . $e->getMessage());
        }
    }
}