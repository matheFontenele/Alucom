<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = ['nome', 'localizacao'];

    //Relacionamento com equipamentos
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }
}
