@extends('layouts.app')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Visão Geral do Sistema</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-printer text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Equipamentos</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalEquipamentos }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-users text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Clientes Ativos</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalClientes }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-warning-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Estoque Baixo</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $estoqueBaixo }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-git-pull-request text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Pendentes</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $requisicoesPendentes }}</h3>
            </div>
        </div>

    </div>
</div>
@endsection