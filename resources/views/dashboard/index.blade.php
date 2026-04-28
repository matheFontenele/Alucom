@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Controle de Requisições')

@section('content')
<div class="p-4 md:p-6 space-y-6">

    {{-- CABEÇALHO --}}
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Painel de Saídas</h1>
            <p class="text-sm text-slate-500">Monitore e processe as solicitações de clientes.</p>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest text-right">
            {{ now()->format('d \d\e F, Y') }}
        </div>
    </div>

    {{-- ALERTA DE REQUISIÇÕES PENDENTES --}}
    @if($requisicoesPendentes > 0)
    <div class="bg-red-50 border border-red-200 p-5 rounded-2xl shadow-sm flex justify-between items-center animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-center gap-4">
            <div class="bg-red-500 p-3 rounded-xl text-white shadow-lg shadow-red-200">
                <i class="ph ph-bell-ringing text-2xl animate-pulse"></i>
            </div>
            <div>
                <h3 class="text-red-900 font-black text-lg leading-tight">ATENÇÃO: {{ $requisicoesPendentes }} PENDENTES</h3>
                <p class="text-red-700 text-sm">Existem pedidos aguardando separação física no estoque.</p>
            </div>
        </div>
        <a href="{{ route('requisicoes.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-black uppercase text-xs transition-all shadow-md">
            PROCESSAR AGORA
        </a>
    </div>
    @endif

    {{-- ÁREA PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- LISTA DE REQUISIÇÕES (OCUPA 3 COLUNAS) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h2 class="font-black text-slate-800 text-sm uppercase tracking-tighter flex items-center gap-2">
                    <i class="ph ph-list-magnifying-glass text-blue-600 text-xl"></i>
                    Fluxo Recente de Solicitações
                </h2>
                <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-1 rounded font-bold uppercase">Últimas 10</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($ultimasRequisicoes as $req)
                <div class="p-5 hover:bg-blue-50/30 transition-colors flex items-center justify-between group">
                    <div class="flex items-center gap-5">
                        <div class="text-center">
                            <span class="block text-[10px] font-black text-slate-400 uppercase">ID</span>
                            <span class="text-sm font-bold text-slate-600">#{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="h-8 w-[1px] bg-gray-200"></div>
                        <div>
                            <p class="text-sm font-black text-slate-800 group-hover:text-blue-700 transition-colors">
                                {{ $req->cliente->nome ?? 'Cliente Geral' }}
                            </p>
                            <p class="text-xs text-slate-500 font-medium">
                                <span class="text-blue-600 font-bold">{{ $req->quantidade }}x</span> {{ $req->item_descricao }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right hidden md:block">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Solicitado em</p>
                            <p class="text-xs font-bold text-slate-600">{{ $req->created_at->format('d/m H:i') }}</p>
                        </div>

                        @php
                        $statusColor = match($req->situacao) {
                        'Pendente' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'Atendida' => 'bg-green-100 text-green-700 border-green-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                        };
                        @endphp
                        <span class="px-3 py-1 rounded-lg border text-[10px] font-black uppercase {{ $statusColor }}">
                            {{ $req->situacao }}
                        </span>

                        <a href="{{ route('requisicoes.show', $req->id) }}" class="p-2 bg-slate-100 rounded-lg text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                            <i class="ph ph-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-20 text-center">
                    <i class="ph ph-tray text-6xl text-slate-200"></i>
                    <p class="text-slate-400 text-sm mt-4 font-medium">Nenhuma requisição registrada no sistema.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- COLUNA LATERAL DE AÇÕES (OCUPA 1 COLUNA) --}}
        <div class="space-y-6">
            <div class="bg-blue-900 rounded-2xl p-6 text-white shadow-xl shadow-blue-900/20">
                <h2 class="font-black text-xs uppercase tracking-widest mb-4 opacity-60 text-blue-200">Operações</h2>
                <div class="space-y-3">
                    <a href="{{ route('requisicoes.create') }}" class="flex items-center justify-between w-full bg-white text-blue-900 p-4 rounded-xl font-black text-xs uppercase hover:bg-blue-50 transition-all shadow-lg">
                        Nova Requisição
                        <i class="ph ph-plus-circle text-lg"></i>
                    </a>
                    <a href="{{ route('requisicoes.index') }}" class="flex items-center justify-between w-full bg-blue-800 text-white p-4 rounded-xl font-black text-xs uppercase hover:bg-blue-700 transition-all border border-blue-700">
                        Ver Todas
                        <i class="ph ph-list-bullets text-lg"></i>
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-blue-800">
                    <h3 class="text-[10px] font-black uppercase text-blue-400 mb-4 tracking-tighter">Resumo de Hoje</h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-blue-800/50 p-3 rounded-lg">
                            <span class="block text-xl font-black">{{ $requisicoesPendentes }}</span>
                            <span class="text-[8px] font-bold uppercase opacity-60 tracking-widest">Aguardando</span>
                        </div>
                        <div class="bg-blue-800/50 p-3 rounded-lg">
                            <span class="block text-xl font-black text-green-400">{{ $ultimasRequisicoes->where('situacao', 'Atendida')->count() }}</span>
                            <span class="text-[8px] font-bold uppercase opacity-60 tracking-widest">Concluídas</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dica Operacional --}}
            <div class="bg-slate-800 rounded-2xl p-6 text-slate-300">
                <div class="flex items-center gap-2 mb-2 text-amber-400">
                    <i class="ph ph-lightbulb-filament text-xl"></i>
                    <span class="font-black text-[10px] uppercase">Dica do Sistema</span>
                </div>
                <p class="text-xs leading-relaxed">
                    Priorize as requisições com status <strong class="text-white font-black">Pendente</strong> para garantir que os prazos de entrega dos clientes sejam cumpridos.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection