<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogo';
    protected $fillable = ['nome', 'fabricante', 'categoria', 'tipo_papel', 'voltagem', 'cor', 'descricao'];

    public function getCorHexAttribute()
    {
        $cores = [
            'Preto'   => '#000000',
            'Ciano'   => '#00FFFF',
            'Magenta' => '#FF00FF',
            'Amarelo' => '#FFFF00',
            'Branco'  => '#FFFFFF',
        ];

        return $cores[$this->cor] ?? '#cbd5e1'; // Cinza se não encontrar
    }
}
