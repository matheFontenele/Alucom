<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clientes extends Model
{
    protected $fillable = [
        'parent_id',
        'tipo',
        'nome',
        'cnpj',
        'estado',
        'cidade',
        'endereco',
        'contrato',
        'sla'
    ];

    protected $casts = [
        'sla' => 'array',
    ];

    // Relacionamento: Pega todas as unidades de um ministério
    public function unidades()
    {
        return $this->hasMany(Clientes::class, 'parent_id');
    }

    // Relacionamento: Pega o ministério ao qual uma unidade pertence
    public function pai()
    {
        return $this->belongsTo(Clientes::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        // Certifique-se de que o nome da coluna no banco é 'parent_id'
        return $this->belongsTo(Clientes::class, 'parent_id');
    }

    /**
     * Define a relação com as "Secretarias/Filhos"
     */
    public function children(): HasMany
    {
        return $this->hasMany(Clientes::class, 'parent_id');
    }
}
