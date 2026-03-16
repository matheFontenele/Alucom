<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuiaAdiController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\TecnicosController;
use App\Http\Controllers\MovimentacaoController;

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