<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    //Pertence a uma Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    //Recebe varios equipamentos
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }
}
