<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuiaEnergia extends Model
{
    protected $fillable = ['fabricante', 'marca_modelo', 'foto', 'potencia_va', 'obs'];
}
