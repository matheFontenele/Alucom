<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuiaMonitor extends Model
{
    protected $fillable = ['fabricante', 'marca_modelo', 'foto', 'polegadas', 'obs'];
}
