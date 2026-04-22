@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão Geral')

@section('content')
<div class="p-4 md:p-6 space-y-8">

    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
            {{ now()->format('d \d\e F, Y') }}
        </div>
    </div>

    {{-- ALERTA DE TOMBAMENTO PENDENTE --}}
    @if($totalPendentes > 0)
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm flex justify-between items-center animate-pulse">
        <div class="flex items-center">
            <div class="bg-amber-100 p-2 rounded-lg mr-4">
                <i class="ph ph-warning-octagon text-amber-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-amber-900 font-black text-sm uppercase tracking-tight">Identificação Pendente</p>
                <p class="text-amber-700 text-xs">Existem <b>{{ $totalPendentes }}</b> equipamentos aguardando numeração de tombo.</p>
            </div>
        </div>
        <a href="{{ route('equipamentos.pendentes') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
            Resolver Agora
        </a>
    </div>
    @endif

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

    {{-- Terceira Linha: Listas Rápidas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Requisições --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <i class="ph ph-list-bullets text-xl text-red-600"></i>
                    <h2 class="font-bold text-slate-800">Requisições Recentes</h2>
                </div>
                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[10px] font-black">
                    {{ $requisicoesPendentes }} PENDENTES
                </span>
            </div>

            @if($requisicoesPendentes > 0)
            <p class="text-slate-500 text-sm mb-4">Existem solicitações aguardando sua análise técnica ou logística.</p>
            <a href="#" class="text-blue-600 font-bold text-xs hover:underline flex items-center gap-1">
                Ver todas as requisições <i class="ph ph-arrow-right"></i>
            </a>
            @else
            <div class="text-center py-4">
                <i class="ph ph-check-circle text-3xl text-slate-200"></i>
                <p class="text-slate-400 text-xs mt-2">Nenhuma requisição pendente.</p>
            </div>
            @endif
        </div>

        {{-- Atalhos Rápidos --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-6">
                <i class="ph ph-lightning text-xl text-blue-600"></i>
                <h2 class="font-bold text-slate-800">Ações Rápidas</h2>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('equipamentos.mass_entry') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
                    <div class="bg-blue-100 p-2 rounded-md text-blue-600">
                        <i class="ph ph-plus-circle"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-600">Entrada Massa</span>
                </a>
                <a href="{{ route('equipamentos.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
                    <div class="bg-slate-100 p-2 rounded-md text-slate-600">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-600">Consultar Serial</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection