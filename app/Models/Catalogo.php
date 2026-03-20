<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogo';
    protected $fillable = ['nome', 'fabricante', 'categoria', 'tipo_papel', 'voltagem', 'cor', 'descricao'];
}
