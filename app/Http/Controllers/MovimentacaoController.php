<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Equipamento;
use App\Models\Clientes;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MovimentacaoController extends Controller
{
    /**
     * Lista o histórico de movimentações com paginação.
     */
    public function index()
    {
        // Carrega os relacionamentos necessários para evitar múltiplas consultas
        $movimentacoes = Movimentacao::with(['equipamento', 'requisicao.cliente'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    /**
     * Formulário para criação de movimentação manual.
     */
    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

    /**
     * Registra uma nova movimentação e sincroniza o estado do equipamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id'    => 'required|exists:equipamentos,id',
            'tipo'              => 'required|string', 
            'situacao'          => 'required|string', 
            'origem'            => 'required|string',
            'destino'           => 'required|string',
            'data_movimentacao' => 'required|date',
            'requisicao_id'     => 'nullable|exists:requisicoes,id',
            'observacao'        => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $equipamento = Equipamento::findOrFail($request->equipamento_id);

            // 1. Atualiza o estado do Equipamento
            $equipamento->status = $request->tipo;
            $equipamento->situacao = $request->situacao;

            // 2. Lógica de Posse (Estoque vs Cliente)
            switch ($request->tipo) {
                case 'Liberado':
                case 'Manutenção':
                case 'Devolução':
                    if (str_contains($request->situacao, 'Estoque') || $request->situacao === 'Liberado') {
                        $equipamento->cliente_id = null;
                        $estoque = Estoque::where('nome', $request->destino)->first();
                        $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                    }
                    break;

                case 'Aluguel':
                case 'Reservado':
                case 'Substituição':
                    if ($request->situacao === 'No Cliente' || $request->situacao === 'Separado') {
                        $equipamento->estoque_id = null;
                        $cliente = Clientes::where('nome', $request->destino)->first();
                        $equipamento->cliente_id = $cliente->id ?? $equipamento->cliente_id;
                    }
                    break;
            }

            $equipamento->save();

            // 3. Cria o registro no histórico
            Movimentacao::create($request->all());

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
        });
    }

    /**
     * Tela de edição de registro de movimentação.
     */
    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacoes.edit', compact('movimentacao'));
    }

    /**
     * Atualiza o registro e sincroniza o substatus no equipamento.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao'   => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            // Atualiza o histórico
            $movimentacao->update($request->only(['situacao', 'observacao']));

            // Sincroniza o substatus no equipamento vinculado
            if ($equipamento) {
                $equipamento->situacao = $request->situacao;
                
                // Se na edição for definido como disponível no estoque
                if (in_array($request->situacao, ['Em Estoque', 'Liberado'])) {
                    $equipamento->status = 'Liberado';
                    $equipamento->cliente_id = null;
                    $estoque = Estoque::where('nome', $movimentacao->destino)->first();
                    $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                }
                
                $equipamento->save();
            }

            return redirect()->route('movimentacoes.index')->with('success', 'Registro atualizado com sucesso!');
        });
    }

    /**
     * Remove um registro de movimentação do histórico.
     */
    public function destroy($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->delete();

        return redirect()->back()->with('success', 'Registro removido do histórico!');
    }

    /**
     * Gera o PDF do Protocolo de Entrega/Movimentação.
     */
    public function emitirProtocolo($id)
    {
        // Carrega a movimentação com todos os dados necessários para o layout do PDF
        $movimentacao = Movimentacao::with(['requisicao.cliente', 'equipamento.catalogo'])->findOrFail($id);

        // Se houver uma requisição vinculada, listamos todos os itens que foram separados nela
        if ($movimentacao->requisicao_id) {
            $itens = Movimentacao::with('equipamento.catalogo')
                ->where('requisicao_id', $movimentacao->requisicao_id)
                ->get();
        } else {
            // Se for movimentação manual, o protocolo contém apenas o item selecionado
            $itens = collect([$movimentacao]);
        }

        // Gera o PDF usando a view específica (que deve estar em resources/views/pdf/protocolo.blade.php)
        $pdf = Pdf::loadView('pdf.protocolo', compact('movimentacao', 'itens'))
            ->setPaper('a4', 'portrait');

        // Retorna o PDF para abrir em nova aba
        return $pdf->stream("Protocolo_REQ_{$movimentacao->requisicao_id}_MOV_{$movimentacao->id}.pdf");
    }
}