<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiddingItem;
use App\Models\BiddingContract;

class BiddingItemController extends Controller
{
    public function store(Request $request)
    {
        // Validação para arrays
        $request->validate([
            'bidding_contract_id'   => 'required|exists:bidding_contracts,id',
            'item_description.*'    => 'required|string',
            'contracted_quantity.*' => 'required|integer',
            'unit_price.*'          => 'required|numeric',
        ]);

        $contract_id = $request->bidding_contract_id;
        $descricoes = $request->item_description;

        foreach ($descricoes as $index => $descricao) {
            \App\Models\BiddingItem::create([
                'bidding_contract_id' => $contract_id,
                'lote'                => $request->lote[$index],
                'item_type'           => $request->item_type[$index],
                'item_description'    => $descricao,
                'contracted_quantity' => $request->contracted_quantity[$index],
                'unit_price'          => $request->unit_price[$index],
                'delivered_quantity'  => 0, // Inicia zerado
            ]);
        }

        return redirect()->back()->with('success', 'Itens adicionados com sucesso!');
    }
    public function destroy(BiddingItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item removido com sucesso!');
    }
}
