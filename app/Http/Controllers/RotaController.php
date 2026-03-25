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
        // Filtra apenas usuários que são motoristas
        $motoristas = User::where('funcao', 'Motorista')->get();
        $veiculos = Veiculo::all();
        $estoques = Estoque::all();

        // Busca requisições que já tiveram o patrimônio separado, 
        // mas que ainda não foram vinculadas a nenhuma rota.
        $requisicoesDisponiveis = Requisicao::whereNotNull('patrimonio_novo')
            ->whereDoesntHave('requisicoes_rota') // Verifica se já está em alguma rota
            ->get();

        return view('rotas.create', compact('motoristas', 'veiculos', 'estoques', 'requisicoesDisponiveis'));
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
            'requisicoes' => 'required|array|min:1', // Pelo menos uma requisição no carregamento
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
                'status' => 'Em Preparação',
                'observacoes' => $request->observacoes,
            ]);

            // 2. Vincular as requisições (Carregamento)
            // O attach() insere na tabela intermediária rota_requisicao
            $rota->requisicoes()->attach($request->requisicoes);

            // 3. Atualizar o status das requisições para "Em Rota"
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

    public function show(Rota $rota)
    {
        $rota->load(['motorista', 'veiculo', 'requisicoes.cliente', 'requisicoes.catalogo']);
        return view('rotas.show', compact('rota'));
    }
}