<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingItem extends Model
{
    protected $fillable = [
        'bidding_contract_id',
        'item_description',
        'quantity',
        'min_cpu',
        'min_ram',
        'min_storage',
        'os_required',
        'reference_model'
    ];

    public function contract()
    {
        return $this->belongsTo(BiddingContract::class, 'bidding_contract_id');
    }
}
