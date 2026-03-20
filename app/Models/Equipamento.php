<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipamento extends Model
{
    protected $fillable = [
        'catalogo_id',    // RELAÇÃO COM O CATÁLOGO
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

    /**
     * Relação com o item original do Catálogo
     */
    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }

    /**
     * Usado na View para exibir o círculo colorido
     */
    public function getCorHexAttribute()
    {
        $cores = [
            'Preto'   => '#000000',
            'Ciano'   => '#00FFFF',
            'Magenta' => '#FF00FF',
            'Amarelo' => '#FFFF00',
            'Branco'  => '#FFFFFF',
        ];

        return $cores[$this->cor] ?? '#cbd5e1'; 
    }

    // --- Outros Relacionamentos ---
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function subcategoria() { return $this->belongsTo(Subcategoria::class); }
    public function cliente() { return $this->belongsTo(Clientes::class, 'cliente_id'); }
    public function estoque() { return $this->belongsTo(Estoque::class); }
    public function movimentacoes() { return $this->hasMany(Movimentacao::class); }
}