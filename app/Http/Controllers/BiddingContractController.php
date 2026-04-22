<?php

namespace App\Http\Controllers;

use App\Models\BiddingContract;
use Illuminate\Http\Request;

class BiddingContractController extends Controller
{
    public function index()
    {
        // Puxa os contratos e conta quantos itens cada um tem
        $licitacoes = BiddingContract::withCount('items')->orderBy('created_at', 'desc')->get();
        return view('licitacoes.index', compact('licitacoes'));
    }

    public function create()
    {
        return view('licitacoes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pregao_number' => 'required|string',
            'uasg_organ' => 'required|string',
            'object' => 'required|string',
            'delivery_deadline' => 'required|integer',
        ]);

        BiddingContract::create($request->all());

        return redirect()->route('licitacoes.index')->with('success', 'Edital cadastrado com sucesso!');
    }

    public function show($id)
    {
        // Carrega o contrato com seus itens técnicos
        $licitacao = BiddingContract::with(['items', 'accessories'])->findOrFail($id);

        return view('licitacoes.show', compact('licitacao'));
    }

    public function edit(BiddingContract $licitacao)
    {
        return view('licitacoes.edit', compact('licitacao'));
    }
}
