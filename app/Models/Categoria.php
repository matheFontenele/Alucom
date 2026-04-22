<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //Recebe varias subcategorias
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }

    //Recebe varios equipamentos
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }
}
