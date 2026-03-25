<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuiaAdiController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\TecnicosController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\UserController;
use App\Models\Subcategoria;
use Illuminate\Support\Facades\Artisan;

// Redirecionamento Inicial
Route::get('/', function () {
    return redirect()->route('guia-adi.index');
});

/**
 * RECURSOS PRINCIPAIS (CRUDs)
 */
Route::resource('guia-adi', GuiaAdiController::class);
Route::resource('clientes', ClientesController::class);
Route::resource('estoques', EstoqueController::class);
Route::resource('tecnicos', TecnicosController::class);
Route::resource('equipamentos', EquipamentoController::class);
Route::resource('movimentacoes', MovimentacaoController::class);
Route::resource('catalogo', CatalogoController::class)->names('catalogos');

/**
 * LOGÍSTICA (Rotas, Veículos e Usuários/Motoristas)
 */
Route::resource('rotas', RotaController::class);
Route::resource('veiculos', VeiculoController::class);
Route::resource('usuarios', UserController::class)->names('usuarios');

/**
 * SISTEMA DE REQUISIÇÕES & SEPARAÇÃO
 */

// 1. Rotas Customizadas de Separação (Devem vir ANTES do resource)
Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])
    ->name('requisicoes.separacao');

Route::put('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])
    ->name('requisicoes.separar.update');

// 2. Resource de Requisições
Route::resource('requisicoes', RequisicaoController::class);


/**
 * ROTAS ESPECÍFICAS E APIs
 */

// Rota de identificação de Categorias e SubCategorias para o Frontend
Route::get('/api/categorias/{categoria}/subcategorias', function ($categoriaId) {
    return Subcategoria::where('categoria_id', $categoriaId)->get();
});

// Rota de detalhes de itens dentro do estoque
Route::get('/estoques/{estoque}/detalhes/{nome}', [EstoqueController::class, 'detalhesItem'])
    ->name('estoques.detalhes-item');


/**
 * UTILITÁRIOS DE MANUTENÇÃO
 */

Route::get('/popular-banco', function () {
    try {
        Artisan::call('db:seed', ['--force' => true]);
        return "Seeders executados com sucesso! <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro ao rodar seeders: " . $e->getMessage();
    }
});

Route::get('/debug-seed', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return "Reset e Seed realizados com sucesso! Output: " . Artisan::output();
    } catch (\Exception $e) {
        return "Erro detectado: " . $e->getMessage() . " em " . $e->getFile() . ":" . $e->getLine();
    }
});