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

            $equipamento->status = $request->tipo;
            $equipamento->situacao = $request->situacao;

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
            Movimentacao::create($request->all());

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
        });
    }

    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacoes.edit', compact('movimentacao'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao'   => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            $movimentacao->update($request->only(['situacao', 'observacao']));

            if ($equipamento) {
                $equipamento->situacao = $request->situacao;

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

    public function destroy($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->delete();

        return redirect()->back()->with('success', 'Registro removido do histórico!');
    }

    /**
     * Gera o PDF do Protocolo com cores dinâmicas.
     */
    public function emitirProtocolo($id)
    {
        ini_set('memory_limit', '512M');

        $movimentacao = Movimentacao::with(['itens.equipamento', 'empresa'])->findOrFail($id);

        // Se a empresa tiver slug 'moreia', o Blade vai buscar 'moreia.png'
        $config = [
            'slug' => $movimentacao->empresa->slug, // Ex: zaploc
            'nome' => $movimentacao->empresa->nome,
            'cor'  => $movimentacao->empresa->cor_principal
        ];

        $pdf = Pdf::loadView('pdf.protocolo', [
            'movimentacao' => $movimentacao,
            'itens' => $movimentacao->itens,
            'config' => $config
        ]);

        return $pdf->stream('protocolo.pdf');
    }
}
