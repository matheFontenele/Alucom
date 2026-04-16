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
        'tipo',             // 'equipamento' ou 'insumo'
        'categoria_id',
        'subcategoria_id',  // Nova relação adicionada
        'processador',      // Específico para Computadores
        'memoria',          // Específico para Computadores
        'geracao',          // Específico para Computadores
        'voltagem',         // Específico para Nobreaks/Eletrônicos
        'tipo_impressora',  // Específico para Impressoras (Mono/Color)
        'tipo_papel',
        'cor',
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
            'Preto'   => '#000000',
            'Ciano'   => '#00FFFF',
            'Magenta' => '#FF00FF',
            'Amarelo' => '#FFFF00',
            'Branco'  => '#FFFFFF',
        ];

        return $cores[$this->cor] ?? '#cbd5e1'; // Cinza padrão
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