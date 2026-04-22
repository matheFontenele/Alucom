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
            'pregao_number'     => 'required|string',
            'uasg_organ'        => 'required|string',
            'object'            => 'required|string',
            'delivery_deadline' => 'required|integer',
            'validity_months'   => 'required|integer',
            'extension_years'   => 'nullable|integer',
        ]);

        // Se validity_months for nulo, define 12 como padrão para não quebrar o banco
        $data['validity_months'] = $request->input('validity_months', 12);
        $data['extension_years'] = $request->input('extension_years', 0);

        $data['accepts_used'] = $request->has('accepts_used');
        $data['requires_office'] = $request->has('requires_office');

        $licitacao = BiddingContract::create($data);

        return redirect()->route('licitacoes.show', $licitacao->id)
            ->with('success', 'Edital criado! Agora adicione os itens técnicos.');
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
