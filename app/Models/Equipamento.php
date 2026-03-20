<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    // Adicionado: tipo, cor, observacoes e estoque_id
    protected $fillable = [
        'categoria_id',
        'subcategoria_id',
        'cliente_id',
        'estoque_id',
        'tipo',          // 'equipamento' ou 'insumo'
        'tombo',
        'nome',
        'serial',
        'status',        // Alugado, Disponivel, etc.
        'situacao',      // No Cliente, Em Rota, etc.
        'cor',           // Preto, Ciano, Magenta, Amarelo
        'observacoes',   // Notas gerais
        'data_movimentacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

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

        return $cores[$this->cor] ?? '#cbd5e1'; // Cinza se não houver cor
    }

    // --- Relacionamentos ---

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}