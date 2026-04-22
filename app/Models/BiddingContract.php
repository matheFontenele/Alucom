<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiddingContract extends Model
{
    protected $fillable = [
        'pregao_number',
        'uasg_organ',
        'object',
        'validity_months',
        'extension_years',
        'delivery_deadline',
        'accepts_used',
        'requires_office',
        'requires_bivolt',
        'maintenance_notes'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BiddingItem::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(BiddingAccessory::class);
    }
}
