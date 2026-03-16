<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';
    protected $fillable = [
        'equipamento_id',
        'cliente_id',
        'estoque_id',
        'tipo',
        'data_movimentacao',
        'observacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class);
    }

    public function estoque(): BelongsTo
    {
        return $this->belongsTo(Estoque::class);
    }
}
