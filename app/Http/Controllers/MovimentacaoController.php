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
            'equipamento_id'    => 'required|exists:equipamentos,id',
            'tipo'              => 'required|string',
            'origem'            => 'required|string',
            'destino'           => 'required|string',
            'data_movimentacao' => 'required|date',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $equipamento = Equipamento::findOrFail($request->equipamento_id);
                $statusAtual = $equipamento->status;
                $novoTipo    = $request->tipo;

                // --- APLICAÇÃO DAS REGRAS DE NEGÓCIO ---

                // 1. Devolução: Cliente -> Estoque
                if ($novoTipo === 'Devolução') {
                    if (!in_array($statusAtual, ['Alugado', 'Reservado'])) {
                        return back()->with('error', 'Só é possível devolver equipamentos Alugados ou Reservados.')->withInput();
                    }
                    $equipamento->status = 'Devolução';
                    $equipamento->cliente_id = null;
                    $equipamento->estoque_id = $request->destino_id ?? $request->destino; 
                }

                // 2. Aluguel: Estoque -> Cliente
                elseif ($novoTipo === 'Aluguel') {
                    if (!in_array($statusAtual, ['Disponivel', 'Reservado'])) {
                        return back()->with('error', 'O item deve estar Disponível ou Reservado para ser alugado.')->withInput();
                    }
                    $equipamento->status = 'Alugado';
                    $equipamento->estoque_id = null;
                    $equipamento->cliente_id = $request->destino_id ?? $request->destino;
                }

                // 3. Manutenção
                elseif ($novoTipo === 'Manutenção') {
                    if ($statusAtual !== 'Devolução') {
                        return back()->with('error', 'O equipamento deve passar pelo status de Devolução antes da Manutenção.')->withInput();
                    }
                    $equipamento->status = 'Manutenção';
                }

                // 4. Liberação (Volta a ficar disponível no estoque atual)
                elseif ($novoTipo === 'Liberação') {
                    if (!in_array($statusAtual, ['Devolução', 'Manutenção'])) {
                        return back()->with('error', 'Só é possível liberar itens vindo de Devolução ou Manutenção.')->withInput();
                    }
                    $equipamento->status = 'Disponivel';
                }

                // 5. Reservado
                elseif ($novoTipo === 'Reservado') {
                    if ($statusAtual !== 'Disponivel') {
                        return back()->with('error', 'Apenas equipamentos Disponíveis podem ser reservados.')->withInput();
                    }
                    $equipamento->status = 'Reservado';
                }

                // 6. Substituição
                elseif ($novoTipo === 'Substituição') {
                    if ($statusAtual !== 'Alugado') {
                        return back()->with('error', 'Substituição só permitida para itens Alugados.')->withInput();
                    }
                    $equipamento->status = 'Devolução';
                    $equipamento->cliente_id = null;
                    $equipamento->estoque_id = $request->destino_id ?? $request->destino;
                }

                // Salva o log da movimentação
                Movimentacao::create($request->all());

                // Atualiza o estado do equipamento no banco
                $equipamento->save();

                return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar movimentação: ' . $e->getMessage())->withInput();
        }
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