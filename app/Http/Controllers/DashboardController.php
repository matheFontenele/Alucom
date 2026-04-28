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
        // Métricas de Equipamentos e Clientes
        $totalEquipamentos = Equipamento::where('tipo', 'equipamento')->count();
        $equipamentosManutencao = Equipamento::where('status', 'Manutenção')->count();
        $totalClientes = Clientes::whereNull('parent_id')->count();
        $totalInsumos = Equipamento::where('tipo', 'insumo')->count();
        $totalPendentes = Equipamento::pendentesTombamento()->count();

        // --- FOCO EM REQUISIÇÕES ---
        // Contagem de requisições por status (usando 'situacao' conforme seu banco)
        $requisicoesPendentes = Requisicao::where('situacao', 'Pendente')->count();
        $requisicoesAtendidas = Requisicao::where('situacao', 'Atendida')->count();

        // Pegar as 5 requisições mais recentes para a lista
        $ultimasRequisicoes = Requisicao::with('cliente')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalEquipamentos',
            'equipamentosManutencao',
            'totalClientes',
            'totalInsumos',
            'totalPendentes',
            'requisicoesPendentes',
            'requisicoesAtendidas',
            'ultimasRequisicoes'
        ));
    }
}
