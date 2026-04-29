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
use App\Http\Controllers\BiddingContractController;
use App\Http\Controllers\BiddingItemController;
use App\Http\Controllers\BiddingAccessoryController;

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
    Route::resource('catalogos', CatalogoController::class);

    // --- LICITAÇÕES (CONTRATOS E ITENS) ---
    Route::resource('licitacoes', BiddingContractController::class);
    Route::resource('licitacoes-itens', BiddingItemController::class);
    Route::resource('licitacoes-acessorios', BiddingAccessoryController::class);

    // --- EQUIPAMENTOS E ENTRADA EM MASSA ---
    Route::get('/equipamentos/mass-entry', [EquipamentoController::class, 'massEntry'])->name('equipamentos.mass_entry');
    Route::get('/insumos/mass-entry', [EquipamentoController::class, 'massEntryInsumo'])->name('insumos.mass_entry');
    Route::post('/equipamentos/store-mass', [EquipamentoController::class, 'storeMass'])->name('equipamentos.store_mass');

    // ROTAS DE TOMBAMENTO POSTERIOR
    Route::get('/equipamentos/pendentes-tombamento', [EquipamentoController::class, 'pendentesTombamento'])->name('equipamentos.pendentes');
    Route::post('/equipamentos/aplicar-tombamento', [EquipamentoController::class, 'aplicarTombamento'])->name('equipamentos.aplicar_tombamento');

    Route::resource('equipamentos', EquipamentoController::class);

    Route::get('/estoques/{estoque}/detalhes/{nome}', [EstoqueController::class, 'detalhesItem'])
        ->name('estoques.detalhes-item');

    // --- LOGÍSTICA (MENU 2) ---
    Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])->name('requisicoes.separacao');
    Route::put('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])->name('requisicoes.separar.update');
    Route::get('/requisicoes/{id}/separacao', [RequisicaoController::class, 'separacao'])->name('requisicoes.separacao');
    Route::post('/requisicoes/{id}/separar', [RequisicaoController::class, 'separarUpdate'])->name('requisicoes.separar.update');

    Route::get('/movimentacoes/{id}/protocolo', [MovimentacaoController::class, 'emitirProtocolo'])->name('movimentacoes.protocolo');
    Route::get('/movimentacoes/{id}/etiqueta', [MovimentacaoController::class, 'emitirEtiqueta'])->name('movimentacoes.etiqueta');

    Route::resource('requisicoes', RequisicaoController::class);
    Route::resource('rotas', RotaController::class);
    Route::resource('movimentacoes', MovimentacaoController::class);
    Route::resource('veiculos', VeiculoController::class);

    Route::get('/rotas/{id}/imprimir', [RotaController::class, 'imprimir'])->name('rotas.imprimir');

    // --- GERENCIAMENTO (MENU 3 - ACESSO RESTRITO) ---
    Route::middleware(['funcao:Direção,Gerência'])->group(function () {
        Route::resource('usuarios', UserController::class)->parameters([
            'usuarios' => 'usuario'
        ]);
    });

    // --- APIs INTERNAS (PARA MODAIS E SELECTS DINÂMICOS) ---
    Route::prefix('api')->group(function () {
        // Busca itens específicos de um estoque
        Route::get('/estoques/{estoque}/itens', [RequisicaoController::class, 'getItensPorEstoque']);

        // Busca subcategorias via AJAX
        Route::get('/categorias/{categoria}/subcategorias', function ($categoriaId) {
            return Subcategoria::where('categoria_id', $categoriaId)->get();
        });

        // ROTA ADICIONADA: Busca sugestões de catálogo para Homologação de Licitação
        Route::get('/sugestoes-estoque', [EquipamentoController::class, 'buscarSugestoesEstoque'])->name('api.sugestoes_estoque');
    });
});

// ---------------------------------------------------------
// 3. MANUTENÇÃO E DEBUG (UTILITÁRIOS)
// ---------------------------------------------------------
Route::get('/limpar-sistema', function () {
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "Todos os caches foram limpos com sucesso!";
})->name('debug.clear');

Route::get('/debug-seed', function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return "Banco resetado e Seeders aplicados! <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Erro ao rodar debug-seed: " . $e->getMessage();
    }
});
