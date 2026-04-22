@extends('layouts.app')

@section('title', 'Licitações')
@section('subtitle', 'Contratos e Editais')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Editais de Licitação</h1>
        <p class="text-slate-500">Acompanhamento de faturamento e saldos de contratos.</p>
    </div>
    <a href="{{ route('licitacoes.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-red-900/20 transition-all">
        <i class="ph ph-plus-circle text-xl"></i>
        Novo Edital
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($licitacoes as $licitacao)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex flex-col">
                    <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase px-2 py-1 rounded-md mb-1 w-fit">
                        Pregão: {{ $licitacao->pregao_number }}
                    </span>
                    @if($licitacao->contract_number)
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Contrato: {{ $licitacao->contract_number }}</span>
                    @endif
                </div>
                <span class="text-slate-400 text-xs"><i class="ph ph-calendar"></i> {{ $licitacao->created_at->format('d/m/Y') }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-2 truncate" title="{{ $licitacao->uasg_organ }}">{{ $licitacao->uasg_organ }}</h3>

            {{-- Barra de Progresso Financeiro --}}
            <div class="mb-4">
                <div class="flex justify-between text-[10px] font-black uppercase mb-1">
                    <span class="text-slate-400">Consumo do Teto</span>
                    <span class="text-slate-600">
                        {{ $licitacao->max_monthly_billing > 0 ? number_format(($licitacao->current_billing / $licitacao->max_monthly_billing) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-red-500 h-full transition-all"
                        style="width: {{ $licitacao->max_monthly_billing > 0 ? min(($licitacao->current_billing / $licitacao->max_monthly_billing) * 100, 100) : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="ph ph-money text-emerald-500"></i>
                        <span>Faturamento:</span>
                    </div>
                    <span class="font-bold text-slate-800">R$ {{ number_format($licitacao->current_billing, 2, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="ph ph-chart-line-up text-orange-500"></i>
                        <span>Saldo Disp:</span>
                    </div>
                    <span class="font-bold {{ $licitacao->available_balance < 0 ? 'text-red-600' : 'text-slate-800' }}">
                        R$ {{ number_format($licitacao->available_balance, 2, ',', '.') }}
                    </span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 pt-2 border-t border-slate-50">
                    <i class="ph ph-package"></i>
                    <span>{{ $licitacao->items_count ?? 0 }} tipos de itens em contrato</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('licitacoes.show', $licitacao->id) }}" class="flex-1 text-center py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-bold text-sm transition">
                    Gerenciar Itens
                </a>
                <a href="{{ route('licitacoes.edit', $licitacao->id) }}" class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                    <i class="ph ph-pencil-line text-xl"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection