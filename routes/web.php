<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuiaAdiController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\TecnicosController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('guia-adi.index');
});

// Guia ADI
Route::resource('guia-adi', GuiaAdiController::class);
Route::prefix('guia')->group(function () {
    Route::get('/', [GuiaAdiController::class, 'index'])->name('guia-adi.index');
    Route::get('/novo', [GuiaAdiController::class, 'create'])->name('guia-adi.create');
    Route::post('/', [GuiaAdiController::class, 'store'])->name('guia-adi.store');
    Route::get('/{id}', [GuiaAdiController::class, 'show'])->name('guia-adi.show');
});

// Clientes
Route::resource('clientes', ClientesController::class);

// Estoques
Route::resource('estoques', EstoqueController::class);

//Tecnicos
Route::resource('tecnicos', TecnicosController::class);

//Equipamentos
Route::resource('equipamentos', EquipamentoController::class);

//Movimentações
Route::resource('movimentacoes', MovimentacaoController::class);

//Catalogo
Route::resource('catalogo', CatalogoController::class)->names('catalogos');

//Rota de identificação de Categorias e SubCategorias
Route::get('/api/categorias/{categoria}/subcategorias', function ($categoriaId) {
    return App\Models\Subcategoria::where('categoria_id', $categoriaId)->get();
});

//Rota de detalhes de itens detro do estoque
Route::get('/estoques/{estoque}/detalhes/{nome}', [App\Http\Controllers\EstoqueController::class, 'detalhesItem'])
    ->name('estoques.detalhes-item');

Route::get('/init-db', function () {
    try {
        // Roda os seeders definidos no DatabaseSeeder
        Artisan::call('db:seed', ['--force' => true]);
        return "Banco de dados povoado com sucesso! " . Artisan::output();
    } catch (\Exception $e) {
        return "Erro ao rodar seed: " . $e->getMessage();
    }
});
