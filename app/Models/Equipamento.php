<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipamento extends Model
{
    protected $table = 'equipamentos';

    protected $fillable = [
        'catalogo_id',
        'categoria_id',
        'subcategoria_id',
        'cliente_id',
        'estoque_id',
        'tipo',
        'tombo',
        'nome',
        'serial',
        'status',
        'situacao',
        'cor',
        'observacoes',
        'data_movimentacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    public function getLocalAtualAttribute(): string
    {
        if (in_array($this->status, ['Alugado', 'Reservado'])) {
            return $this->cliente?->nome ?? 'Cliente não identificado';
        }

        return $this->estoque?->nome ?? 'Local Indefinido';
    }

    public function getCorHexAttribute(): string
    {
        $cores = [
            'Preto'   => '#000000',
            'Ciano'   => '#00FFFF',
            'Magenta' => '#FF00FF',
            'Amarelo' => '#FFFF00',
            'Branco'  => '#FFFFFF',
        ];

        return $cores[$this->cor] ?? '#cbd5e1'; // Cinza padrão se não encontrar
    }

    public function getPrecisaTombamentoAttribute(): bool
    {
        return is_null($this->tombo) && $this->tipo === 'equipamento';
    }

    public function scopePendentesTombamento($query)
    {
        return $query->whereNull('tombo')->where('tipo', 'equipamento');
    }

    // --- RELACIONAMENTOS ---

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }

    public function estoque(): BelongsTo
    {
        return $this->belongsTo(Estoque::class);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }
}
