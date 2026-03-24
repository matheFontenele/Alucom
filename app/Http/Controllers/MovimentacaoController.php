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
    public function index()
    {
        $movimentacoes = Movimentacao::with(['equipamento'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }


    public function create()
    {
        $equipamentos = Equipamento::all();
        $clientes = Clientes::all();
        $estoques = Estoque::all();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id' => 'required',
            'tipo' => 'required',
            'origem' => 'required',
            'destino' => 'required',
            'data_movimentacao' => 'required|date',
        ]);

        $equipamento = \App\Models\Equipamento::findOrFail($request->equipamento_id);
        $statusAtual = $equipamento->status;
        $novoTipo = $request->tipo;

        // --- APLICAÇÃO DAS REGRAS ALUCOM ---

        // 1. Devolução: Cliente -> Estoque
        if ($novoTipo === 'Devolução') {
            if (!in_array($statusAtual, ['Alugado', 'Reservado'])) {
                return back()->with('error', 'Só é possível devolver equipamentos Alugados ou Reservados.');
            }
            $equipamento->status = 'Devolução';
            $equipamento->cliente_id = null; // Sai do cliente
            $equipamento->estoque_id = $request->destino; // Volta para o estoque selecionado no form
        }

        // 2. Aluguel: Estoque -> Cliente
        elseif ($novoTipo === 'Aluguel') {
            if (!in_array($statusAtual, ['Disponivel', 'Reservado'])) {
                return back()->with('error', 'O item deve estar Disponível ou Reservado para ser alugado.');
            }
            $equipamento->status = 'Alugado';
            $equipamento->estoque_id = null; // Sai do estoque
            $equipamento->cliente_id = $request->destino; // Vai para o cliente selecionado no form
        }

        // 3. Manutenção: Só após Devolução
        elseif ($novoTipo === 'Manutenção') {
            if ($statusAtual !== 'Devolução') {
                return back()->with('error', 'O equipamento deve passar pelo status de Devolução antes da Manutenção.');
            }
            $equipamento->status = 'Manutenção';
        }

        // 4. Liberação: Após Devolução ou Manutenção
        elseif ($novoTipo === 'Liberação') {
            if (!in_array($statusAtual, ['Devolução', 'Manutenção'])) {
                return back()->with('error', 'Só é possível liberar itens vindo de Devolução ou Manutenção.');
            }
            $equipamento->status = 'Disponivel';
        }

        // 5. Reservado: Só se estiver Liberado (Disponível)
        elseif ($novoTipo === 'Reservado') {
            if ($statusAtual !== 'Disponivel') {
                return back()->with('error', 'Apenas equipamentos Disponíveis podem ser reservados.');
            }
            $equipamento->status = 'Reservado';
        }

        // 6. Substituição: Gera devolução do antigo
        elseif ($novoTipo === 'Substituição') {
            if ($statusAtual !== 'Alugado') {
                return back()->with('error', 'Substituição só permitida para itens Alugados.');
            }
            $equipamento->status = 'Devolução';
            $equipamento->cliente_id = null;
            $equipamento->estoque_id = $request->destino; // Volta pro estoque
        }
        // Salva a movimentação e atualiza o equipamento
        \App\Models\Movimentacao::create($request->all());
        $equipamento->save();

        return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
    }
}
