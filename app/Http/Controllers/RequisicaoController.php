<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Clientes;
use App\Models\Catalogo;
use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class RequisicaoController extends Controller
{
    /**
     * Exibe a lista de requisições
     */
    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'item'])->orderBy('created_at', 'desc')->paginate(10);
        return view('requisicoes.index', compact('requisicoes'));
    }

    /**
     * Formulário de criação de nova requisição
     */
    public function create()
    {
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();
        
        // O catálogo inicia vazio na View pois será preenchido via JS conforme o estoque
        $catalogo = []; 

        return view('requisicoes.create', compact('clientes', 'estoques', 'catalogo'));
    }

    /**
     * API: Retorna itens do catálogo que possuem saldo no estoque selecionado
     */
    public function getItensPorEstoque($estoqueId)
    {
        $itens = Catalogo::whereHas('equipamentos', function($q) use ($estoqueId) {
            $q->where('estoque_id', $estoqueId)
              ->where('status', 'Disponivel');
        })
        ->withCount(['equipamentos' => function($q) use ($estoqueId) {
            $q->where('estoque_id', $estoqueId)
              ->where('status', 'Disponivel');
        }])
        ->orderBy('nome')
        ->get();

        return response()->json($itens);
    }

    /**
     * Salva a nova requisição
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'   => 'required',
            'catalogo_id'  => 'required',
            'estoque_id'   => 'required',
            'quantidade'   => 'required|integer|min:1',
            'envio'        => 'required',
            'etiqueta'     => 'required',
        ]);

        Requisicao::create([
            'situacao'               => 'Pendente',
            'oficio'                 => $request->oficio ?? 'Sem Oficio',
            'solicitante'            => auth()->user() ? auth()->user()->name : 'Admin',
            'data_solicitacao'       => now(),
            'previsao_envio'         => $request->previsao_envio,
            'envio'                  => $request->envio ?? 'Coleta',
            'nfe'                    => $request->nfe ?? 'Sem NF',
            'cliente_id'             => $request->cliente_id,
            'catalogo_id'            => $request->catalogo_id,
            'estoque_id'             => $request->estoque_id,
            'estado'                 => $request->estado,
            'cidade'                 => $request->cidade,
            'etiqueta'               => $request->etiqueta ?? 'Alucom',
            'quantidade'             => $request->quantidade,
            'tipo_solicitacao'       => $request->tipo_solicitacao,
            'patrimonio_substituido' => $request->tipo_solicitacao === 'Substituição' ? $request->patrimonio_substituido : null,
        ]);

        return redirect()->route('requisicoes.index')->with('success', 'Requisição criada com sucesso!');
    }

    /**
     * Detalhes da requisição
     */
    public function show($id)
    {
        $requisicao = Requisicao::with(['cliente', 'item', 'estoque'])->findOrFail($id);
        return view('requisicoes.show', compact('requisicao'));
    }

    /**
     * Tela de Separação de Materiais
     */
    public function separacao($id)
    {
        $requisicao = Requisicao::with(['cliente', 'item'])->findOrFail($id);

        // Filtra os tombos disponíveis APENAS no estoque que foi selecionado na requisição
        $tombosDisponiveis = Equipamento::where('catalogo_id', $requisicao->catalogo_id)
            ->where('estoque_id', $requisicao->estoque_id)
            ->where('status', 'Disponivel')
            ->orderBy('tombo', 'asc')
            ->get();

        return view('requisicoes.separacao', compact('requisicao', 'tombosDisponiveis'));
    }

    /**
     * Finaliza a separação e gera a movimentação
     */
    public function separarUpdate(Request $request, $id)
    {
        $requisicao = Requisicao::findOrFail($id);

        $requisicao->update([
            'situacao'             => 'Finalizada',
            'quantidade_separada'  => $request->quantidade_separada,
            'data_separacao'       => $request->data_separacao,
            'separado_por'         => $request->separado_por,
            'baixa_sistema'        => $request->baixa_sistema,
            'observacao_separacao' => $request->observacao_separacao,
            'patrimonio_novo'      => $request->patrimonio_novo,
        ]);

        if ($request->baixa_sistema == '1') {
            $equipamentoFisico = Equipamento::where('tombo', $request->patrimonio_novo)->first();

            if ($equipamentoFisico) {
                Movimentacao::create([
                    'equipamento_id'    => $equipamentoFisico->id,
                    'tipo'              => 'Aluguel',
                    'origem'            => $requisicao->estoque->nome ?? 'Estoque Central',
                    'situacao'          => 'Aguardando Rota',
                    'destino'           => $requisicao->cliente_id,
                    'data_movimentacao' => now(),
                    'detalhes'          => "Saída via Req #{$requisicao->id}. Substituindo: {$requisicao->patrimonio_substituido} por: {$request->patrimonio_novo}"
                ]);

                $equipamentoFisico->update(['status' => 'Alugado']);
            }
        }

        return redirect()->route('requisicoes.index')->with('success', 'Separação concluída!');
    }
}