@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão Geral')

@section('content')
<div class="p-4 md:p-6 space-y-6">

    {{-- CABEÇALHO --}}
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-800">Painel Operacional</h1>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
            {{ now()->format('d \d\e F, Y') }}
        </div>
    </div>

    {{-- ALERTAS CRÍTICOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Alerta de Requisições --}}
        @if($requisicoesPendentes > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex justify-between items-center group">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                    <i class="ph ph-bell-ringing text-red-600 text-2xl animate-bounce"></i>
                </div>
                <div>
                    <p class="text-red-900 font-black text-sm uppercase">Requisições Pendentes</p>
                    <p class="text-red-700 text-xs font-medium">Existem <b>{{ $requisicoesPendentes }}</b> solicitações aguardando separação.</p>
                </div>
            </div>
            <a href="{{ route('requisicoes.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all">
                Verificar
            </a>
        </div>
        @endif

        {{-- Alerta de Tombamento --}}
        @if($totalPendentes > 0)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm flex justify-between items-center group">
            <div class="flex items-center">
                <div class="bg-amber-100 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                    <i class="ph ph-barcode text-amber-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-amber-900 font-black text-sm uppercase">Tombamento Pendente</p>
                    <p class="text-amber-700 text-xs font-medium"><b>{{ $totalPendentes }}</b> itens novos sem número de tombo.</p>
                </div>
            </div>
            <a href="{{ route('equipamentos.pendentes') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all">
                Identificar
            </a>
        </div>
        @endif
    </div>

    {{-- CARDS DE MÉTRICAS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card Requisições --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-blue-300 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-2 rounded-lg text-blue-600"><i class="ph ph-clipboard-text text-2xl"></i></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Geral</span>
            </div>
            <h3 class="text-3xl font-black text-slate-700">{{ $requisicoesPendentes + $requisicoesAtendidas }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase mt-1">Requisições no Mês</p>
        </div>

        {{-- Outros cards simplificados --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-emerald-50 p-2 rounded-lg text-emerald-600"><i class="ph ph-user-list text-2xl"></i></div>
            </div>
            <h3 class="text-3xl font-black text-slate-700">{{ $totalClientes }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase mt-1">Clientes Ativos</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-purple-50 p-2 rounded-lg text-purple-600"><i class="ph ph-printer text-2xl"></i></div>
            </div>
            <h3 class="text-3xl font-black text-slate-700">{{ $totalEquipamentos }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase mt-1">Equipamentos</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-orange-50 p-2 rounded-lg text-orange-600"><i class="ph ph-wrench text-2xl"></i></div>
            </div>
            <h3 class="text-3xl font-black text-slate-700">{{ $equipamentosManutencao }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase mt-1">Em Manutenção</p>
        </div>
    </div>

    {{-- LISTA DE REQUISIÇÕES RECENTES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h2 class="font-black text-slate-800 text-sm uppercase tracking-tighter flex items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-blue-600 text-lg"></i>
                    Últimas Requisições
                </h2>
                <a href="{{ route('requisicoes.index') }}" class="text-blue-600 font-bold text-[10px] uppercase hover:underline">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($ultimasRequisicoes as $req)
                <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-xs">
                            #{{ $req->id }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $req->cliente->nome ?? 'Cliente não identificado' }}</p>
                            <p class="text-[10px] text-slate-500 uppercase font-medium">{{ $req->item_descricao }} • {{ $req->quantidade }} unid.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $req->situacao == 'Pendente' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                            {{ $req->situacao }}
                        </span>
                        <a href="{{ route('requisicoes.show', $req->id) }}" class="text-slate-400 hover:text-blue-600">
                            <i class="ph ph-caret-right font-bold"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <p class="text-slate-400 text-sm">Nenhuma requisição encontrada.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ATALHOS LATERAIS --}}
        <div class="space-y-6">
            <div class="bg-blue-900 rounded-2xl p-6 text-white shadow-lg shadow-blue-900/20 relative overflow-hidden">
                <i class="ph ph-rocket-launch absolute -right-4 -bottom-4 text-8xl opacity-10"></i>
                <h2 class="font-bold text-lg mb-2">Ações Rápidas</h2>
                <p class="text-blue-200 text-xs mb-6">Inicie processos logísticos comuns com um clique.</p>
                <div class="space-y-3">
                    <a href="{{ route('requisicoes.create') }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/20 p-3 rounded-xl flex items-center gap-3 transition-all">
                        <i class="ph ph-plus-circle text-xl"></i>
                        <span class="text-xs font-bold uppercase tracking-tight">Nova Requisição</span>
                    </a>
                    <a href="{{ route('equipamentos.mass_entry') }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/20 p-3 rounded-xl flex items-center gap-3 transition-all">
                        <i class="ph ph-package text-xl"></i>
                        <span class="text-xs font-bold uppercase tracking-tight">Entrada em Massa</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection