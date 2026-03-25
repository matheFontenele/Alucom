<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    protected $fillable = [
        'user_id',
        'veiculo_id',
        'estoque_origem_id',
        'cidade_destino',
        'estado_destino',
        'data_saida',
        'previsao_chegada',
        'status',
        'observacoes'
    ];

    public function requisicoes()
    {
        return $this->belongsToMany(Requisicao::class, 'rota_requisicao');
    }

    public function motorista()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
}
