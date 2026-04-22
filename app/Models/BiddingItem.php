<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingItem extends Model
{
    protected $fillable = [
        'bidding_contract_id',
        'lote',
        'item_type',
        'item_description',
        'unit_price',
        'contracted_quantity',
        'delivered_quantity',
        'min_cpu',
        'min_ram',
        'min_storage',
        'os_required',
        'billing_reference_id'
    ];

    // Calcula o subtotal deste item específico
    public function getSubtotalAttribute()
    {
        return $this->delivered_quantity * $this->unit_price;
    }

    // Calcula o saldo de equipamentos que ainda podem ser entregues
    public function getEquipmentBalanceAttribute()
    {
        return $this->contracted_quantity - $this->delivered_quantity;
    }
}
