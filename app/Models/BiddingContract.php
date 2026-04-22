<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiddingContract extends Model
{
    protected $fillable = [
        'contract_number',
        'pregao_number',
        'uasg_organ',
        'object',
        'max_monthly_billing',
        'validity_months',
        'delivery_deadline',
        'start_date',
        'end_date',
        'accepts_used',
        'requires_office',
        'maintenance_notes',
        'addendum_summary'
    ];

    // Relacionamento com os itens
    public function items()
    {
        return $this->hasMany(BiddingItem::class);
    }

    // Calcula o total atual de faturamento do contrato (Soma de todos os itens entregues)
    public function getCurrentBillingAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->delivered_quantity * $item->unit_price;
        });
    }

    // Calcula quanto ainda resta de saldo mensal perante o teto (max_monthly_billing)
    public function getAvailableBalanceAttribute()
    {
        return $this->max_monthly_billing - $this->current_billing;
    }
}
