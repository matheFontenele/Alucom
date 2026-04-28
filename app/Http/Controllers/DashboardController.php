<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
use App\Models\Clientes;
use App\Models\Estoque;
use App\Models\Requisicao;

class DashboardController extends Controller
{
    public function index()
    {
        // Contagem de alertas
        $requisicoesPendentes = Requisicao::where('situacao', 'Pendente')->count();

        // Lista principal (Últimas 10 para dar mais corpo à página)
        $ultimasRequisicoes = Requisicao::with('cliente')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'requisicoesPendentes',
            'ultimasRequisicoes'
        ));
    }
}
