<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiddingItem;
use App\Models\BiddingContract;

class BiddingItemController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'bidding_contract_id' => 'required|exists:bidding_contracts,id',
            'item_description'    => 'required|string',
            'lote'                => 'nullable|string',
            'item_type'           => 'nullable|string',
            'contracted_quantity' => 'required|integer',
            'delivered_quantity'  => 'required|integer',
            'unit_price'          => 'required|numeric',
        ]);

        BiddingItem::create($data);

        return redirect()->back()->with('success', 'Item adicionado com sucesso!');
    }

    public function destroy(BiddingItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item removido com sucesso!');
    }
}
