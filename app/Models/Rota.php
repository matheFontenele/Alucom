<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    public function requisicoes()
    {
        return $this->belongsToMany(Requisicao::class, 'rota_requisicao');
    }
}
