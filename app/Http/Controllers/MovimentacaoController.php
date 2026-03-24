<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Equipamento;
use App\Models\Clientes;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentacaoController extends Controller
{
    /**
     * Lista o histórico de movimentações com paginação.
     */
    public function index()
    {
        $movimentacoes = Movimentacao::with(['equipamento'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    /**
     * Exibe o formulário para nova movimentação.
     */
    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

    /**
     * Registra a movimentação e atualiza o estado do equipamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id' => 'required|exists:equipamentos,id',
            'tipo' => 'required',
            'origem' => 'required',
            'destino' => 'required',
            'data_movimentacao' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $equipamento = Equipamento::findOrFail($request->equipamento_id);
            $statusAtual = $equipamento->status;
            $novoTipo = $request->tipo;
            $nomeDestino = $request->destino;

            // --- LÓGICA DE ATUALIZAÇÃO DO EQUIPAMENTO ---

            if ($novoTipo === 'Devolução' || $novoTipo === 'Substituição') {
                $equipamento->status = 'Devolução';
                $equipamento->cliente_id = null;
                // Busca o ID do estoque pelo nome enviado pelo form
                $estoque = Estoque::where('nome', $nomeDestino)->first();
                $equipamento->estoque_id = $estoque ? $estoque->id : $equipamento->estoque_id;
            } elseif ($novoTipo === 'Aluguel') {
                $equipamento->status = 'Alugado';
                $equipamento->estoque_id = null;
                // Busca o ID do cliente pelo nome enviado pelo form
                $cliente = Clientes::where('nome', $nomeDestino)->first();
                $equipamento->cliente_id = $cliente ? $cliente->id : $equipamento->cliente_id;
            } elseif ($novoTipo === 'Manutenção') {
                $equipamento->status = 'Manutenção';
            } elseif ($novoTipo === 'Liberação') {
                $equipamento->status = 'Disponivel';
            } elseif ($novoTipo === 'Reservado') {
                $equipamento->status = 'Reservado';
            }

            // Salva a movimentação (o model já aceita as strings no $fillable)
            Movimentacao::create($request->all());

            // Salva a alteração no equipamento
            $equipamento->save();

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada!');
        });
    }

    /**
     * Remove um registro do histórico de movimentações.
     */
    public function destroy($id)
    {
        try {
            $movimentacao = Movimentacao::findOrFail($id);
            $movimentacao->delete();

            return redirect()->back()->with('success', 'Histórico de movimentação removido!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível excluir o registro.');
        }
    }
}
