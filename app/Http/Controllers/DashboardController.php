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
        // 1. MÉTRICAS DE EQUIPAMENTOS
        $totalEquipamentos = Equipamento::where('tipo', 'equipamento')->count();

        // Filtros por Status
        $equipamentosDisponiveis = Equipamento::where('status', 'Disponivel')->count();
        $equipamentosAlugados = Equipamento::where('status', 'Alugado')->count();
        $equipamentosManutencao = Equipamento::where('status', 'Manutenção')->count();

        // 2. MÉTRICAS DE INSUMOS E CLIENTES
        // Apenas clientes principais (ajuste se seu sistema usar parent_id para unidades)
        $totalClientes = Clientes::whereNull('parent_id')->count();

        $totalInsumos = Equipamento::where('tipo', 'insumo')->count();

        // 3. LOGÍSTICA E REQUISIÇÕES
        try {
            // Note que usei 'status' ou 'situacao' conforme o padrão das suas views anteriores
            $requisicoesPendentes = Requisicao::where('status', 'Pendente')->count();
        } catch (\Exception $e) {
            $requisicoesPendentes = 0;
        }

        // 4. LOCAIS DE ESTOQUE
        $totalLocaisEstoque = Estoque::count();

        // 5. NOVA MÉTRICA: EQUIPAMENTOS SEM TOMBO (Entrada em Massa)
        $totalPendentes = Equipamento::pendentesTombamento()->count();

        return view('dashboard.index', compact(
            'totalEquipamentos',
            'equipamentosDisponiveis',
            'equipamentosAlugados',
            'equipamentosManutencao',
            'totalClientes',
            'totalInsumos',
            'requisicoesPendentes',
            'totalLocaisEstoque',
            'totalPendentes'
        ));
    }
}
