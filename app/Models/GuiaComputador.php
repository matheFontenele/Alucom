<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuiaComputador extends Model
{

    protected $table = 'guia_computadores';
    protected $fillable = ['fabricante', 'marca_modelo', 'foto', 'processador', 'memoria', 'armazenamento', 'geracao', 'obs'];
}
