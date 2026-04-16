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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Models\Subcategoria;

// ---------------------------------------------------------
// 1. ROTAS PÚBLICAS
// ---------------------------------------------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ---------------------------------------------------------
// 2. ÁREA RESTRITA (PRECISA ESTAR LOGADO)
// ---------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD PRINCIPAL ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- OPERAÇÕES (MENU 1) ---
    Route::resource('guia-adi', GuiaAdiController::class);
    Route::resource('clientes', ClientesController::class);
    Route::resource('tecnicos', TecnicosController::class);
    Route::resource('estoques', EstoqueController::class);
    
    /**
     * Catálogo de Ativos
     * O resource gerencia: index, create, store, show, edit, update, destroy
     */
    Route::resource('catalogos', CatalogoController::class);

    // --- EQUIPAMENTOS E ENTRADA EM MASSA ---
    // Importante: Rotas específicas de Massa devem vir ANTES do resource
    Route::get('/equipamentos/mass-entry', [EquipamentoController::class, 'massEntry'])->name('equipamentos.mass_entry');
    Route::get('/insumos/mass-entry', [EquipamentoController::class, 'massEntryInsumo'])->name('insumos.mass_entry');
    Route::post('/equipamentos/store-mass', [EquipamentoController::class, 'storeMass'])->name('equipamentos.store_mass');

    Route::resource('equipamentos', EquipamentoController::class);

    Route::get('/estoques/{estoque}/detalhes/{nome}', [EstoqueController::class, 'detalhesItem'])
        ->name('estoques.detalhes-item');

    // --- LOGÍSTICA (MENU 2) ---
    Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])->name('requisicoes.separacao');
    Route::put('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])->name('requisicoes.separar.update');
    
    Route::get('/movimentacoes/{id}/protocolo', [MovimentacaoController::class, 'emitirProtocolo'])->name('movimentacoes.protocolo');
    Route::get('/movimentacoes/{id}/etiqueta', [MovimentacaoController::class, 'emitirEtiqueta'])->name('movimentacoes.etiqueta');

    Route::resource('requisicoes', RequisicaoController::class);
    Route::resource('rotas', RotaController::class);
    Route::resource('movimentacoes', MovimentacaoController::class);
    Route::resource('veiculos', VeiculoController::class);

    // Rota de Impressão de Rotas
    Route::get('/rotas/{id}/imprimir', [RotaController::class, 'imprimir'])->name('rotas.imprimir');

    // --- GERENCIAMENTO (MENU 3) ---
    Route::middleware(['funcao:Direção,Gerência'])->group(function () {
        Route::resource('usuarios', UserController::class)->parameters([
            'usuarios' => 'usuario'
        ]);
    });

    // --- APIs INTERNAS ---
    Route::prefix('api')->group(function () {
        Route::get('/estoques/{estoque}/itens', [RequisicaoController::class, 'getItensPorEstoque']);
        Route::get('/categorias/{categoria}/subcategorias', function ($categoriaId) {
            return Subcategoria::where('categoria_id', $categoriaId)->get();
        });
    });
});

// ---------------------------------------------------------
// 3. MANUTENÇÃO E DEBUG
// ---------------------------------------------------------

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
        Artisan::call('optimize:clear');
        Artisan::call('migrate:fresh', ['--force' => true]);
        $output = "Migrations executadas.<br>";

        $seederPath = database_path('seeders/CategoriaEquipamentoSeeder.php');
        if (file_exists($seederPath)) {
            require_once $seederPath;
            Artisan::call('db:seed', ['--force' => true]);
            $output .= "Seeder executado com sucesso!<br>";
        } else {
            return "Erro: Arquivo não encontrado em " . $seederPath;
        }

        return $output . "<br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage() . "<br>Linha: " . $e->getLine();
    }
});

Route::get('/limpar-rota', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "Todos os caches do Laravel foram limpos!";
});