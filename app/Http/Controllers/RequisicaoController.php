<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Clientes;
use App\Models\Catalogo;
use App\Models\Movimentacao;
use Illuminate\Http\Request;

class RequisicaoController extends Controller
{
    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'item'])->orderBy('created_at', 'desc')->paginate(10);
        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        $clientes = Clientes::orderBy('nome')->get();
        $catalogo = Catalogo::orderBy('nome')->get();
        return view('requisicoes.create', compact('clientes', 'catalogo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'catalogo_id' => 'required',
            'quantidade' => 'required|integer|min:1',
            'envio' => 'required',
            'etiqueta' => 'required',
        ]);

        Requisicao::create([
            'oficio' => $request->oficio ?? 'Sem Oficio',
            'solicitante' => auth()->user() ? auth()->user()->name : 'Admin',
            'data_solicitacao' => now(),
            'previsao_envio' => $request->previsao_envio,
            'envio' => $request->envio ?? 'Coleta',
            'nfe' => $request->nfe ?? 'Sem NF',
            'cliente_id' => $request->cliente_id,
            'catalogo_id' => $request->catalogo_id,
            'estado' => $request->estado,
            'cidade' => $request->cidade,
            'etiqueta' => $request->etiqueta ?? 'Alucom',
            'quantidade' => $request->quantidade,
            'tipo_solicitacao' => $request->tipo_solicitacao,
            'patrimonio_substituido' => $request->tipo_solicitacao === 'Substituição' ? $request->patrimonio_substituido : null,
        ]);

        return redirect()->route('requisicoes.index')->with('success', 'Requisição criada com sucesso!');
    }

    public function show($id)
    {
        $requisicao = Requisicao::with(['cliente', 'item'])->findOrFail($id);
        return view('requisicoes.show', compact('requisicao'));
    }

    public function separacao($id)
    {
        $requisicao = Requisicao::with(['cliente', 'item'])->findOrFail($id);
        return view('requisicoes.separacao', compact('requisicao'));
    }

    public function separarUpdate(Request $request, $id)
    {
        $requisicao = Requisicao::findOrFail($id);

        $requisicao->update([
            'quantidade_separada' => $request->quantidade_separada,
            'data_separacao' => $request->data_separacao,
            'separado_por' => $request->separado_por,
            'baixa_sistema' => $request->baixa_sistema,
            'observacao_separacao' => $request->observacao_separacao,
        ]);

        // Se marcou "Sim" para baixa (valor '1'), gera movimentação automática
        if ($request->baixa_sistema == '1') {
            Movimentacao::create([
                'equipamento_id' => $requisicao->catalogo_id,
                'tipo' => 'Aluguel',
                'situacao' => 'Aguardando Rota',
                'destino' => $requisicao->cliente_id,
                'data_movimentacao' => now(),
                'detalhes' => "Saída automática via Requisição #{$requisicao->id}"
            ]);
        }

        return redirect()->route('requisicoes.index')
            ->with('success', 'Separação concluída e movimentação gerada!');
    }
}
