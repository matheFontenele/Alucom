<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Controllers\CatalogoController;

class Requisicao extends Model
{

    protected $table = 'requisicoes';
    protected $fillable = [
        'oficio',
        'solicitante',
        'data_solicitacao',
        'previsao_envio',
        'envio',
        'nfe',
        'cliente_id',
        'catalogo_id',
        'estoque_id',
        'estado',
        'cidade',
        'etiqueta',
        'quantidade',
        'tipo_solicitacao',
        'patrimonio_substituido',
        'patrimonio_novo',
        'quantidade_separada',
        'data_separacao',
        'separado_por',
        'baixa_sistema',
        'observacao_separacao',
        'situacao',
        'rota_id'
    ];

    public function estoque(): BelongsTo
    {
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class);
    }

    public function item()
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }

    public function rotas()
    {
        return $this->belongsToMany(Rota::class, 'rota_requisicao');
    }
}
