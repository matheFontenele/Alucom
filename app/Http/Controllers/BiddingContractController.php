<?php

namespace App\Http\Controllers;

use App\Models\BiddingContract;
use Illuminate\Http\Request;

class BiddingContractController extends Controller
{
    public function index()
    {
        // Puxa os contratos e conta itens
        $licitacoes = BiddingContract::withCount('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('licitacoes.index', compact('licitacoes'));
    }

    public function create()
    {
        return view('licitacoes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contract_number'     => 'nullable|string', // Ex: 2021.08.02.01-19
            'pregao_number'       => 'required|string',
            'uasg_organ'          => 'required|string',
            'object'              => 'required|string',
            'max_monthly_billing' => 'required|numeric', // Teto financeiro
            'delivery_deadline'   => 'required|integer',
            'validity_months'     => 'required|integer',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
        ]);

        // Tratamento de Checkboxes
        $data['accepts_used'] = $request->has('accepts_used');
        $data['requires_office'] = $request->has('requires_office');

        // Notas Adicionais
        $data['maintenance_notes'] = $request->input('maintenance_notes');
        $data['addendum_summary'] = $request->input('addendum_summary');

        $licitacao = BiddingContract::create($data);

        return redirect()->route('licitacoes.show', $licitacao->id)
            ->with('success', 'Contrato criado! Agora adicione os itens e quantidades.');
    }

    public function show($id)
    {
        // Carrega o contrato com itens
        // Note: Se você removeu 'accessories' na migração, remova do with() aqui
        $licitacao = BiddingContract::with(['items'])->findOrFail($id);

        return view('licitacoes.show', compact('licitacao'));
    }

    public function edit(BiddingContract $licitacao)
    {
        return view('licitacoes.edit', compact('licitacao'));
    }

    public function update(Request $request, BiddingContract $licitacao)
    {
        $data = $request->validate([
            'contract_number'     => 'nullable|string',
            'pregao_number'       => 'required|string',
            'uasg_organ'          => 'required|string',
            'max_monthly_billing' => 'required|numeric',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            // ... adicione outros campos conforme necessário
        ]);

        $data['accepts_used'] = $request->has('accepts_used');
        $data['requires_office'] = $request->has('requires_office');

        $licitacao->update($data);

        return redirect()->route('licitacoes.show', $licitacao->id)
            ->with('success', 'Contrato atualizado com sucesso!');
    }
}
