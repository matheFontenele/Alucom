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
        $movimentacoes = Movimentacao::with(['equipamento', 'cliente', 'estoque'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id' => 'required|exists:equipamentos,id',
            'tipo'           => 'required|in:Aluguel,Devolução,Substituição',
            'data_movimentacao' => 'required|date',
        ]);

        $equipamento = Equipamento::findOrFail($request->equipamento_id);

        return DB::transaction(function () use ($request, $equipamento) {
            
            switch ($request->tipo) {
                case 'Aluguel':
                    // REGRA: Somente se estiver em estoque
                    if ($equipamento->cliente_id !== null) {
                        return back()->withErrors(['erro' => 'Este equipamento já está com um cliente.']);
                    }
                    
                    $equipamento->update([
                        'cliente_id' => $request->cliente_id,
                        'estoque_id' => null,
                        'situacao'   => 'Alugado'
                    ]);
                    break;

                case 'Devolução':
                    // REGRA: Não pode devolver para um cliente
                    if (!$request->estoque_id) {
                        return back()->withErrors(['erro' => 'Selecione um estoque para a devolução.']);
                    }
                    
                    $equipamento->update([
                        'cliente_id' => null,
                        'estoque_id' => $request->estoque_id,
                        'situacao'   => 'Disponivel'
                    ]);
                    break;

                case 'Substituição':
                    // REGRA: Equipamento saindo deve estar em cliente, entrando deve vir de estoque
                    // (Lógica para o item que está entrando no cliente)
                    $equipamento->update([
                        'cliente_id' => $request->cliente_id,
                        'estoque_id' => null,
                        'situacao'   => 'Alugado'
                    ]);
                    break;
            }

            Movimentacao::create($request->all());

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação realizada!');
        });
    }
}