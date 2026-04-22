<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiddingAccessory extends Model
{
    protected $fillable = [
        'bidding_contract_id',
        'name',
        'included',
        'observation'
    ];

    // Cast para garantir que o banco trate como booleano
    protected $casts = [
        'included' => 'boolean',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(BiddingContract::class, 'bidding_contract_id');
    }
}
