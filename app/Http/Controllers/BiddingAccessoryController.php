<?php

namespace App\Http\Controllers;

use App\Models\BiddingAccessory;
use Illuminate\Http\Request;

class BiddingAccessoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bidding_contract_id' => 'required|exists:bidding_contracts,id',
            'name' => 'required|string',
        ]);

        BiddingAccessory::create($request->all());

        return back()->with('success', 'Acessório adicionado ao edital!');
    }

    public function update(Request $request, BiddingAccessory $acessorio)
    {
        // Útil para atualizar o status "included" via AJAX ou Form rápido
        $acessorio->update($request->only('included', 'observation'));

        return back()->with('success', 'Status do acessório atualizado.');
    }

    public function destroy(BiddingAccessory $acessorio)
    {
        $acessorio->delete();
        return back()->with('success', 'Acessório removido.');
    }
}
