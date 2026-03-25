<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Estoque;
use App\Models\Requisicao;
use Illuminate\Http\Request;

class RotaController extends Controller
{
    public function create()
    {
        // Pegamos apenas usuários que têm a função de 'Motorista'
        $motoristas = User::where('funcao', 'Motorista')->get();
        $veiculos = Veiculo::all();
        $estoques = Estoque::all();
        
        // Pegamos requisições que já foram SEPARADAS mas ainda não estão em nenhuma rota
        $requisicoesDisponiveis = Requisicao::whereNotNull('data_separacao')
            ->whereDoesntHave('rotas') // Certifique-se de ter o relation no Model Requisicao
            ->get();

        return view('rotas.create', compact('motoristas', 'veiculos', 'estoques', 'requisicoesDisponiveis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'veiculo_id' => 'required',
            'estoque_origem_id' => 'required',
            'cidade_destino' => 'required',
            'data_saida' => 'required|date',
            'requisicoes' => 'required|array' // O Carregamento
        ]);

        // 1. Cria a Rota
        $rota = Rota::create($request->all());

        // 2. Vincula as requisições selecionadas à rota (Tabela rota_requisicao)
        if ($request->has('requisicoes')) {
            $rota->requisicoes()->attach($request->requisicoes);
            
            // 3. Opcional: Atualiza o status das requisições para "Em Rota"
            Requisicao::whereIn('id', $request->requisicoes)->update(['situacao' => 'Em Rota']);
        }

        return redirect()->route('rotas.index')->with('success', 'Rota e Carregamento criados!');
    }
}