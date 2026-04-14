@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão Geral')

@section('content')
<div class="p-4 md:p-6 space-y-8">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <div class="text-sm font-medium text-slate-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
            <span class="text-red-500">●</span> Status do Sistema: Online
        </div>
    </div>

    {{-- Grid de Cards Estilo Alcom --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-600 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Equipamentos</p>
                <h3 class="text-3xl font-extrabold text-slate-700">{{ $totalEquipamentos }}</h3>
            </div>
            <div class="opacity-20">
                <i class="ph ph-printer text-5xl text-slate-400"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-1">Insumos</p>
                <h3 class="text-3xl font-extrabold text-slate-700">{{ $totalInsumos }}</h3>
            </div>
            <div class="opacity-20">
                <i class="ph ph-arrows-clockwise text-5xl text-slate-400"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-cyan-500 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-cyan-500 uppercase tracking-wider mb-1">Manutenções</p>
                <h3 class="text-3xl font-extrabold text-slate-700">{{ $equipamentosManutencao }}</h3>
            </div>
            <div class="opacity-20">
                <i class="ph ph-wrench text-5xl text-slate-400"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-1">Clientes</p>
                <h3 class="text-3xl font-extrabold text-slate-700">{{ $totalClientes }}</h3>
            </div>
            <div class="opacity-20">
                <i class="ph ph-user-plus text-5xl text-slate-400"></i>
            </div>
        </div>

    </div>

    {{-- Segunda linha opcional: Requisições ou Gráficos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-list-bullets text-xl text-red-600"></i>
            <h2 class="font-bold text-slate-800">Requisições Recentes</h2>
        </div>
        <p class="text-slate-500 text-sm">Você tem <span class="font-bold text-red-600">{{ $requisicoesPendentes }}</span> requisições aguardando processamento.</p>
    </div>

</div>
@endsection