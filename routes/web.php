<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

// Importação dos Controllers
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

// ---------------------------------------------------------
// 1. REDIRECIONAMENTO E AUTH
// ---------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('guia-adi.index');
});

// Rota de Logout (Garante que a sessão seja limpa corretamente)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// ---------------------------------------------------------
// 2. OPERAÇÕES (MENU 1)
// ---------------------------------------------------------
Route::resource('guia-adi', GuiaAdiController::class);
Route::resource('clientes', ClientesController::class);
Route::resource('catalogos', CatalogoController::class);
Route::resource('tecnicos', TecnicosController::class);
Route::resource('estoques', EstoqueController::class);
Route::resource('equipamentos', EquipamentoController::class);

// Detalhes específicos do estoque
Route::get('/estoques/{estoque}/detalhes/{nome}', [EstoqueController::class, 'detalhesItem'])
    ->name('estoques.detalhes-item');


// ---------------------------------------------------------
// 3. LOGÍSTICA (MENU 2)
// ---------------------------------------------------------

// Rotas de Separação (Devem vir ANTES do resource de requisições)
Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])->name('requisicoes.separacao');
Route::put('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])->name('requisicoes.separar.update');

Route::resource('requisicoes', RequisicaoController::class);
Route::resource('rotas', RotaController::class);
Route::resource('movimentacoes', MovimentacaoController::class);
Route::resource('veiculos', VeiculoController::class);


// ---------------------------------------------------------
// 4. GERENCIAMENTO (MENU 3)
// ---------------------------------------------------------
// Definimos o parâmetro como 'usuario' para bater com o que está no seu UserController
Route::resource('usuarios', UserController::class)->parameters([
    'usuarios' => 'usuario'
]);


// ---------------------------------------------------------
// 5. APIs E UTILITÁRIOS
// ---------------------------------------------------------
Route::get('/api/categorias/{categoria}/subcategorias', function ($categoriaId) {
    return Subcategoria::where('categoria_id', $categoriaId)->get();
});


// ---------------------------------------------------------
// 6. Comando para autentificação de usuarios
// ---------------------------------------------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ---------------------------------------------------------
// 7. MANUTENÇÃO (Comandos Artisan via URL)
// ---------------------------------------------------------

// Popula o banco sem resetar tudo
Route::get('/popular-banco', function () {
    try {
        Artisan::call('db:seed', ['--force' => true]);
        return "Seeders executados! <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});

// RESET TOTAL: Cuidado, apaga todos os dados e recria (Ótimo para corrigir erros de estrutura)
Route::get('/debug-seed', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return "O banco foi resetado e os dados iniciais foram carregados! <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});

// Limpeza de cache (Essencial após mudar rotas ou controllers no Render)
Route::get('/limpar-rota', function() {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "Todos os caches do Laravel foram limpos!";
});