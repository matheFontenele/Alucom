<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Clientes;
use App\Models\Catalogo;
use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $catalogo = [];

        return view('requisicoes.create', compact('clientes', 'estoques', 'catalogo'));
    }

    /**
     * API: Retorna itens do catálogo que possuem saldo no estoque selecionado
     */
    public function getItensPorEstoque($estoqueId)
    {
        $itens = Catalogo::whereHas('equipamentos', function ($q) use ($estoqueId) {
            $q->where('estoque_id', $estoqueId)
                ->where('status', 'Disponivel');
        })
            ->withCount(['equipamentos' => function ($q) use ($estoqueId) {
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
        // Validação básica para os dados do cliente (comuns a todos)
        $request->validate([
            'cliente_id' => 'required',
            'item_nome.*' => 'required', // Valida cada nome de item no array
        ]);

        $itens = $request->input('item_nome');

        DB::transaction(function () use ($request, $itens) {
            foreach ($itens as $index => $nome) {
                Requisicao::create([
                    'situacao'         => 'Pendente',
                    'oficio'           => $request->oficio ?? 'Sem Oficio',
                    'solicitante'      => auth()->user()->name,
                    'data_solicitacao' => now(),
                    'cliente_id'       => $request->cliente_id,
                    'cidade'           => $request->cidade,
                    'estado'           => $request->estado,
                    'etiqueta'         => $request->etiqueta,
                    'previsao_envio'   => $request->previsao_envio,
                    'envio'            => $request->envio,

                    // Dados da "Planilha"
                    'item_descricao'   => $nome,
                    'quantidade'       => $request->item_qtd[$index] ?? 1,
                    'categoria'        => $request->item_categoria[$index] ?? 'Equipamento',
                    'tipo_solicitacao' => $request->item_tipo[$index] ?? 'Novo',
                ]);
            }
        });

        return redirect()->route('requisicoes.index')->with('success', count($itens) . ' itens registrados com sucesso!');
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

        $tombosDisponiveis = Equipamento::where('catalogo_id', $requisicao->catalogo_id)
            ->where('estoque_id', $requisicao->estoque_id)
            ->where('status', 'Disponivel')
            ->orderBy('tombo', 'asc')
            ->get();

        return view('requisicoes.separacao', compact('requisicao', 'tombosDisponiveis'));
    }

    /**
     * Finaliza a separação e inicia o rastreio (Status: Separado)
     */
    public function separarUpdate(Request $request, $id)
    {
        $requisicao = Requisicao::with(['cliente', 'estoque'])->findOrFail($id);

        if ($request->baixa_sistema == '1' && !$request->patrimonio_novo) {
            return back()->with('error', 'Você precisa selecionar um patrimônio para dar baixa no sistema!');
        }

        return DB::transaction(function () use ($request, $requisicao) {

            // 1. Determina o Tipo de Movimentação para o Histórico
            $tipoMov = ($requisicao->tipo_solicitacao === 'Substituição') ? 'Substituição' : 'Aluguel';

            // 2. Atualiza os dados da Requisição
            $requisicao->update([
                'situacao'             => 'Finalizada', // Indica que saiu da fila de pendentes
                'quantidade_separada'  => $request->quantidade_separada,
                'data_separacao'       => $request->data_separacao,
                'separado_por'         => $request->separado_por,
                'baixa_sistema'        => $request->baixa_sistema,
                'observacao_separacao' => $request->observacao_separacao,
                'patrimonio_novo'      => $request->patrimonio_novo,
            ]);

            // 3. Processa o Equipamento Físico e gera o primeiro marco de movimentação
            if ($request->baixa_sistema == '1') {
                $equipamentoFisico = Equipamento::where('tombo', $request->patrimonio_novo)->first();

                if ($equipamentoFisico) {
                    // CRIA O REGISTRO DE MOVIMENTAÇÃO QUE O ROTA-CONTROLLER VAI BUSCAR DEPOIS
                    Movimentacao::create([
                        'equipamento_id'    => $equipamentoFisico->id,
                        'requisicao_id'     => $requisicao->id,
                        'tipo'              => $tipoMov,
                        'situacao'          => 'Separado', // REGRA DE OURO: Deve ser exatamente "Separado"
                        'origem'            => $requisicao->estoque->nome ?? 'Estoque Central',
                        'destino'           => 'Aguardando Rota',
                        'data_movimentacao' => now(),
                        'observacao'        => "Item separado. Aguardando transporte. Req #{$requisicao->id}."
                    ]);

                    // ATUALIZA O EQUIPAMENTO NO CADASTRO GERAL
                    $equipamentoFisico->update([
                        'status'            => 'Alugado',
                        'situacao'          => 'Separado', // Alinha o sub-status do equipamento
                        'cliente_id'        => $requisicao->cliente_id,
                        'estoque_id'        => null, // Sai do estoque e fica "na mão" do sistema
                        'data_movimentacao' => now(),
                    ]);
                }
            }

            return redirect()->route('requisicoes.index')->with('success', 'Separação concluída! O item agora está disponível para ser incluído em uma rota.');
        });
    }
}
