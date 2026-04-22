@extends('layouts.app')

@section('title', 'Detalhes da Licitação')
@section('subtitle', 'Visualização de Edital')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('licitacoes.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-slate-400 hover:text-red-600 transition">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $licitacao->uasg_organ }}</h1>
            <p class="text-slate-500 uppercase text-xs font-black tracking-widest">Pregão Eletrônico {{ $licitacao->pregao_number }}</p>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('licitacoes.edit', $licitacao->id) }}" class="bg-white border border-gray-200 text-slate-600 px-4 py-2 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-50 transition">
            <i class="ph ph-pencil-line"></i>
            Editar Edital
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Coluna da Esquerda: Itens Técnicos --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-list-numbers text-red-500"></i>
                Itens Exigidos no Edital
            </h2>

            @forelse($licitacao->items as $item)
            <div class="border-b border-gray-100 last:border-0 pb-6 mb-6 last:pb-0 last:mb-0">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-bold text-slate-700">{{ $item->item_description }}</h4>
                        <span class="text-xs text-slate-400 font-medium">Quantidade: {{ $item->quantity }} unidades</span>
                    </div>
                    {{-- Badge de Match (Lógica para implementar futuramente) --}}
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-tighter">Match Sugerido</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] text-slate-400 block uppercase font-black tracking-tight">Processador</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->min_cpu }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] text-slate-400 block uppercase font-black tracking-tight">Memória RAM</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->min_ram }}GB</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] text-slate-400 block uppercase font-black tracking-tight">Armazenamento</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->min_storage }}GB SSD</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] text-slate-400 block uppercase font-black tracking-tight">Sistema</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->os_required }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-slate-400 text-sm italic">Nenhum item técnico cadastrado para este edital.</p>
            @endforelse
        </div>

        {{-- Objeto do Edital --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="ph ph-info text-blue-500"></i>
                Objeto da Licitação
            </h2>
            <p class="text-slate-600 text-sm leading-relaxed italic">
                "{{ $licitacao->object }}"
            </p>
        </div>
    </div>

    {{-- Coluna da Direita: Status, Acessórios e Prazos --}}
    <div class="space-y-6">
        {{-- Card de Configurações Gerais --}}
        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-900/20">
            <h3 class="font-bold mb-6 flex items-center gap-2">
                <i class="ph ph-sliders-horizontal text-red-500"></i>
                Configuração Geral
            </h3>
            <ul class="space-y-4">
                <li class="flex items-center justify-between text-sm border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Aceita Seminovos?</span>
                    <span class="{{ $licitacao->accepts_used ? 'text-emerald-400' : 'text-red-400' }} font-black">
                        {{ $licitacao->accepts_used ? 'SIM' : 'NÃO' }}
                    </span>
                </li>
                <li class="flex items-center justify-between text-sm border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Prazo de Entrega</span>
                    <span class="text-white font-black">{{ $licitacao->delivery_deadline }} Dias</span>
                </li>
                <li class="flex items-center justify-between text-sm border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Exige Office?</span>
                    <span class="text-white font-black">{{ $licitacao->requires_office ? 'SIM' : 'NÃO' }}</span>
                </li>
                <li class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">Vigência</span>
                    <span class="text-white font-black">{{ $licitacao->validity_months }} Meses</span>
                </li>
            </ul>
        </div>

        {{-- Card de Acessórios (Checklist) --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-plug-connected text-amber-500"></i>
                Acessórios Inclusos
            </h2>

            <div class="space-y-3">
                @forelse($licitacao->accessories as $accessory)
                <div class="flex items-center justify-between p-3 {{ $accessory->included ? 'bg-emerald-50 border-emerald-100' : 'bg-gray-50 border-gray-100' }} border rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <i class="ph {{ $accessory->included ? 'ph-check-circle text-emerald-600 font-bold' : 'ph-minus-circle text-slate-300' }} text-xl"></i>
                        <span class="text-sm font-bold {{ $accessory->included ? 'text-emerald-900' : 'text-slate-400' }}">
                            {{ $accessory->name }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-xs italic text-center">Nenhum acessório listado.</p>
                @endforelse
            </div>
        </div>

        {{-- Card de Manutenção / Notas --}}
        @if($licitacao->maintenance_notes)
        <div class="bg-amber-50 rounded-3xl p-8 border border-amber-100">
            <h3 class="text-amber-800 font-bold mb-2 flex items-center gap-2 text-sm">
                <i class="ph ph-warning-octagon text-lg"></i>
                Notas de Manutenção
            </h3>
            <p class="text-amber-700 text-xs leading-relaxed">
                {{ $licitacao->maintenance_notes }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection