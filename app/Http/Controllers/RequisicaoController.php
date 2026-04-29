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
     * Exibe a lista de requisições com status atualizados
     */
    public function index()
    {
        // Carrega apenas relações existentes: cliente e estoque
        $requisicoes = Requisicao::with(['cliente', 'estoque'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();
        $catalogo = [];

        return view('requisicoes.create', compact('clientes', 'estoques', 'catalogo'));
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'item_nome.*' => 'required',
            'estoque_id' => 'required',
        ]);

        $itens = $request->input('item_nome');

        DB::transaction(function () use ($request, $itens) {
            foreach ($itens as $index => $nome) {
                Requisicao::create([
                    'situacao'         => 'Pendente', // Status inicial padrão
                    'oficio'           => $request->oficio ?? 'Sem Oficio',
                    'solicitante'      => auth()->user()->name,
                    'data_solicitacao' => now(),
                    'cliente_id'       => $request->cliente_id,
                    'cidade'           => $request->cidade,
                    'estado'           => $request->estado,
                    'etiqueta'         => $request->etiqueta,
                    'previsao_envio'   => $request->previsao_envio,
                    'envio'            => $request->envio,
                    'estoque_id'       => $request->estoque_id,
                    'catalogo_id'      => $request->item_catalogo_id[$index] ?? 1,
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
     * Detalhes da requisição (Corrigido erro de RelationNotFound)
     */
    public function show($id)
    {
        // Removido 'item' do with()
        $requisicao = Requisicao::with(['cliente', 'estoque'])->findOrFail($id);
        return view('requisicoes.show', compact('requisicao'));
    }

    /**
     * Tela de Separação de Materiais (Corrigido erro de RelationNotFound)
     */
    public function separacao($id)
    {
        $requisicao = Requisicao::with(['cliente', 'estoque', 'catalogo'])->findOrFail($id);

        // Busca itens disponíveis especificamente deste modelo no estoque da requisição
        $tombosDisponiveis = Equipamento::where('catalogo_id', $requisicao->catalogo_id)
            ->where('estoque_id', $requisicao->estoque_id)
            ->where('status', 'Disponivel')
            ->orderBy('tombo', 'asc')
            ->get();

        $estoqueVazio = $tombosDisponiveis->isEmpty();

        return view('requisicoes.separacao', compact('requisicao', 'tombosDisponiveis', 'estoqueVazio'));
    }

    public function separarUpdate(Request $request, $id)
    {
        $requisicao = Requisicao::findOrFail($id);
        $quantidadeParaBaixa = $request->has('atendimento_completo')
            ? $requisicao->quantidade
            : $request->quantidade_separada;

        // 1. Decidir o novo Status
        $novoStatus = ($quantidadeParaBaixa >= $requisicao->quantidade)
            ? 'Finalizada'
            : 'Parcialmente Atendida';

        // 2. Lógica de Baixa no Estoque (Exemplo)
        // Aqui você faria o loop ou o update no seu banco de patrimônios
        // decrementando a quantidade ou marcando os IDs selecionados como 'Saída'.

        // 3. Atualizar a Requisição
        $requisicao->update([
            'situacao' => $novoStatus,
            'quantidade_atendida' => $quantidadeParaBaixa, // Seria bom ter essa coluna
            'data_separacao' => now(),
            'separado_por' => auth()->user()->name
        ]);

        return redirect()->route('requisicoes.index')
            ->with('success', "Separação realizada! Status: $novoStatus");
    }
}
