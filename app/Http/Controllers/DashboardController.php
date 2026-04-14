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
        // Coletando os números principais
        $totalEquipamentos = Equipamento::count();
        $totalClientes = Clientes::count();
        
        // Exemplo: Peças com estoque baixo (menos de 5 unidades)
        $estoqueBaixo = Estoque::where('quantidade', '<', 5)->count();
        
        // Exemplo: Requisições pendentes hoje
        $requisicoesPendentes = Requisicao::where('status', 'pendente')->count();

        return view('dashboard', compact(
            'totalEquipamentos', 
            'totalClientes', 
            'estoqueBaixo', 
            'requisicoesPendentes'
        ));
    }
}
