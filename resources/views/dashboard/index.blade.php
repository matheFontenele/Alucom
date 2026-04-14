@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão Geral')

@section('content')
<div class="p-2 md:p-6 space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Painel de Controle</h1>
        <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium">
            Atualizado em: {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>

    {{-- Linha 1: Status da Frota de Equipamentos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Disponíveis</p>
                <h3 class="text-2xl font-bold text-emerald-600">{{ $equipamentosDisponiveis }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-handshake text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Alugados</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ $equipamentosAlugados }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-wrench text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Em Manutenção</p>
                <h3 class="text-2xl font-bold text-red-600">{{ $equipamentosManutencao }}</h3>
            </div>
        </div>
    </div>

    {{-- Linha 2: Clientes, Insumos e Logística --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center group">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-users text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-slate-500 font-medium">Clientes Ativos</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalClientes }}</h3>
            </div>
            <a href="{{ route('clientes.index') }}" class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-400 hover:text-purple-600">
                <i class="ph ph-arrow-square-out text-xl"></i>
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-drop text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Insumos em Estoque</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalInsumos }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                <i class="ph ph-clipboard-text text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Requisições Pendentes</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $requisicoesPendentes }}</h3>
            </div>
        </div>

    </div>
</div>
@endsection