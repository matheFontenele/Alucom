@extends('layouts.app')

@section('title', 'Licitações')
@section('subtitle', 'Contratos e Editais')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Editais de Licitação</h1>
        <p class="text-slate-500">Gerencie prazos, itens e conformidades técnicas.</p>
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
                <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase px-2 py-1 rounded-md">Pregão: {{ $licitacao->pregao_number }}</span>
                <span class="text-slate-400"><i class="ph ph-calendar"></i> {{ $licitacao->created_at->format('d/m/Y') }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $licitacao->uasg_organ }}</h3>
            <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $licitacao->object }}</p>

            <div class="space-y-2 mb-6">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <i class="ph ph-clock text-red-500"></i>
                    <span>Prazo Entrega: <strong>{{ $licitacao->delivery_deadline }} dias</strong></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <i class="ph ph-check-square text-emerald-500"></i>
                    <span>{{ $licitacao->items_count ?? 0 }} Itens Cadastrados</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('licitacoes.show', $licitacao->id) }}" class="flex-1 text-center py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition">
                    Detalhes
                </a>
                <a href="{{ route('licitacoes.edit', $licitacao->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                    <i class="ph ph-pencil-line text-xl"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection