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
        'tipo',
        'categoria_id',
        'subcategoria',
        'processador',
        'memoria',
        'geracao',
        'voltagem',
        'polegadas',
        'cor',
        'tipo_insumo',
        'descricao'
    ];

    /**
     * Relação com a Categoria Pai
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relação com a Subcategoria (Ex: Micro, Notebook, Toners)
     */
    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }

    /**
     * Itens físicos vinculados a este modelo do catálogo
     */
    public function equipamentos(): HasMany
    {
        return $this->hasMany(Equipamento::class, 'catalogo_id');
    }

    /**
     * Retorna o código hexadecimal da cor para exibição no front-end
     */
    public function getCorHexAttribute()
    {
        $cores = [
            'Preto'   => '#1e293b',
            'Ciano'   => '#06b6d4',
            'Magenta' => '#d946ef',
            'Amarelo' => '#eab308',
            'Mono'    => '#64748b',
            'Branco'  => '#f8fafc',
        ];

        return $cores[$this->cor] ?? '#cbd5e1';
    }

    /**
     * Verifica se o item é um insumo.
     */
    public function ehInsumo(): bool
    {
        if ($this->tipo) {
            return $this->tipo === 'insumo';
        }

        if (!$this->categoria) {
            return false;
        }

        $categoriasInsumos = ['Suprimentos', 'Toner', 'Cartucho', 'Tintas', 'Papel'];
        return in_array($this->categoria->nome, $categoriasInsumos);
    }
}
