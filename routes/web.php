<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

// 1. REDIRECIONAMENTO E AUTH
Route::get('/', function () {
    return redirect()->route('guia-adi.index');
});

// Rota de Logout (Necessária para o botão da Sidebar)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// 2. OPERAÇÕES (MENU 1)
Route::resource('guia-adi', GuiaAdiController::class);
Route::resource('clientes', ClientesController::class);
Route::resource('catalogos', CatalogoController::class); // Removido o ->names() para evitar confusão
Route::resource('tecnicos', TecnicosController::class);
Route::resource('estoques', EstoqueController::class);
Route::resource('equipamentos', EquipamentoController::class);

// Rota específica de detalhes do estoque
Route::get('/estoques/{estoque}/detalhes/{nome}', [EstoqueController::class, 'detalhesItem'])
    ->name('estoques.detalhes-item');


// 3. LOGÍSTICA (MENU 2)

// Rotas de Separação (Sempre antes do resource de requisições)
Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])->name('requisicoes.separacao');
Route::put('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])->name('requisicoes.separar.update');

Route::resource('requisicoes', RequisicaoController::class);
Route::resource('rotas', RotaController::class);
Route::resource('movimentacoes', MovimentacaoController::class);
Route::resource('veiculos', VeiculoController::class);


// 4. GERENCIAMENTO (MENU 3)
Route::resource('usuarios', UserController::class); // Simplificado para garantir o nome 'usuarios.index'


// 5. APIs E UTILITÁRIOS
Route::get('/api/categorias/{categoria}/subcategorias', function ($categoriaId) {
    return Subcategoria::where('categoria_id', $categoriaId)->get();
});

// MANUTENÇÃO (Acesso via URL)
Route::get('/popular-banco', function () {
    try {
        Artisan::call('db:seed', ['--force' => true]);
        return "Seeders executados! <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});

Route::get('/debug-seed', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return "Reset e Seed realizados! Output: " . Artisan::output();
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});

Route::get('/limpar-rota', function() {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Caches limpos com sucesso!";
});