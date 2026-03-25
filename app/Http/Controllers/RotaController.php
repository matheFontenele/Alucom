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

        // Busca apenas requisições que ainda não foram vinculadas a nenhuma rota
        // Verifica se não há registros na tabela intermediária para aquela requisição
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
        ]);

        try {
            DB::beginTransaction();

            $rota = Rota::create([
                'user_id' => $request->user_id,
                'veiculo_id' => $request->veiculo_id,
                'estoque_origem_id' => $request->estoque_origem_id,
                'cidade_destino' => $request->cidade_destino,
                'estado_destino' => $request->estado_destino,
                'data_saida' => $request->data_saida,
                'previsao_chegada' => $request->previsao_chegada,
                'status' => 'Em Preparação',
                'observacoes' => $request->observacoes,
            ]);

            // Vincula os IDs recebidos do formulário à rota
            $rota->requisicoes()->attach($request->requisicoes);

            // Atualiza a situação para "Em Rota"
            Requisicao::whereIn('id', $request->requisicoes)->update([
                'situacao' => 'Em Rota'
            ]);

            DB::commit();
            return redirect()->route('rotas.index')->with('success', 'Rota criada e carga vinculada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar rota: ' . $e->getMessage());
        }
    }
}