<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalogo extends Model
{
    protected $table = 'catalogo';
    
    protected $fillable = [
        'nome',
        'fabricante',
        'categoria_id',
        'tipo_papel',
        'voltagem',
        'cor',
        'descricao'
    ];

    // Relação com categorias
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function equipamentos(): HasMany
    {
        return $this->hasMany(Equipamento::class, 'catalogo_id');
    }

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

    public function ehInsumo(): bool
    {
        if (!$this->categoria) {
            return false;
        }

        $categoriasInsumos = ['Suprimentos', 'Toner', 'Cartucho', 'Tintas', 'Papel'];
        return in_array($this->categoria->nome, $categoriasInsumos);
    }
}