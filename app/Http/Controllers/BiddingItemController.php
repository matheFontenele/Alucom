<?php

namespace App\Http\Controllers;

use App\Models\BiddingItem;
use Illuminate\Http\Request;

class BiddingItemController extends Controller
{
    public function store(Request $request)
    {
        BiddingItem::create($request->all());
        return back()->with('success', 'Item técnico adicionado ao edital!');
    }

    public function destroy(BiddingItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item removido.');
    }
}
