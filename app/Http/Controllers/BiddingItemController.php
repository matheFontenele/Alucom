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
        'contract_quantity', // O total do contrato
        'delivered_quantity', // O que está instalado/faturando
        'min_cpu',
        'min_ram',
        'min_storage',
        'os_required',
        'billing_reference_id'
    ];

    public function contract()
    {
        return $this->belongsTo(BiddingContract::class, 'bidding_contract_id');
    }

    // Valor da linha na planilha (Qtd Entregue x Preço Unitário)
    public function getSubtotalAttribute()
    {
        return $this->delivered_quantity * $this->unit_price;
    }

    // Quanto ainda pode ser entregue deste item
    public function getEquipmentBalanceAttribute()
    {
        return $this->contract_quantity - $this->delivered_quantity;
    }
}
