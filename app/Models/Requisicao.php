<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'observacao_separacao'
    ];

    public function cliente()
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
