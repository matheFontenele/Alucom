<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    //Itens padrões
    protected $fillable = [
        'categoria_id',
        'subcategoria_id',
        'tombo',
        'nome',
        'serial',
        'status',
        'sub_staus',
        'cliente_id',
        'data_movimentacao'
    ];

    //Data da Movimentação
    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    //Relacionamento com Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    //Relacionamento com subcategoria
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    //Relacionamento com cliente
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }

    //Relacionamento com estoque
    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    //Relacionamento com movimentacoes
    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
