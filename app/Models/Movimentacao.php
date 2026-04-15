<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'requisicao_id',
        'equipamento_id',
        'tipo',
        'situacao',
        'origem',
        'destino',
        'data_movimentacao',
        'observacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    /**
     * Relacionamento com o Equipamento movimentado.
     */
    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }
    /**
     * Relacionamento com a Requisição responsavel pela movimnetação.
     */
    public function requisicao(): BelongsTo
    {
        return $this->belongsTo(Requisicao::class);
    }
}
