<?php

namespace App\Http\Controllers;

use App\Models\BiddingItem;
use Illuminate\Http\Request;

class BiddingItemController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'bidding_contract_id' => 'required|exists:bidding_contracts,id',
            'item_description'    => 'required|string',
            'quantity'            => 'required|integer|min:1',
        ]);

        \App\Models\BiddingItem::create($data);

        return redirect()->back()->with('success', 'Item adicionado com sucesso!');
    }

    public function destroy(BiddingItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item removido.');
    }
}
