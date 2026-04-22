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

        {{-- Card de Cadastro e Listagem de Itens --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-list-numbers text-red-500"></i>
                Itens Exigidos no Edital
            </h2>

            {{-- Formulário de Adição --}}
            <div class="bg-slate-50 rounded-2xl p-6 border border-dashed border-slate-300 mb-8">
                <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Novo Item Técnico</h3>
                <form action="{{ route('licitacoes-itens.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    @csrf
                    <input type="hidden" name="bidding_contract_id" value="{{ $licitacao->id }}">

                    <div class="md:col-span-8">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Descrição do Item (Especificações)</label>
                        <input type="text" name="item_description" placeholder="Ex: Computador i5, 8GB RAM, SSD 240GB + Monitor 19"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-red-500 transition text-sm" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Qtd</label>
                        {{-- Removido qualquer limitador e garantido largura suficiente --}}
                        <input type="number" name="quantity" placeholder="0" min="1" step="1"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-red-500 transition text-sm" required>
                    </div>

                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition flex items-center justify-center gap-2 text-sm shadow-lg shadow-red-200">
                            <i class="ph ph-plus-bold"></i> Add
                        </button>
                    </div>
                </form>
            </div>

            {{-- Listagem de Itens Cadastrados --}}
            <div class="space-y-4">
                @forelse($licitacao->items as $item)
                <div class="group flex justify-between items-center p-4 bg-white border border-gray-100 rounded-2xl hover:border-red-200 transition-all shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-red-50 text-red-600 font-black px-3 py-2 rounded-xl text-sm">
                            {{ number_format($item->quantity, 0, ',', '.') }}x
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700 text-sm">{{ $item->item_description }}</h4>
                            @if($item->min_cpu && $item->min_cpu !== 'N/A')
                            <span class="text-[10px] text-slate-400 font-medium uppercase">{{ $item->min_cpu }} | {{ $item->min_ram }}GB RAM | {{ $item->min_storage }}GB SSD</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('licitacoes-itens.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Excluir este item?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-slate-300 hover:text-red-600 transition">
                            <i class="ph ph-trash text-lg"></i>
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="ph ph-package text-4xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 text-sm italic">Nenhum item técnico cadastrado ainda.</p>
                </div>
                @endforelse
            </div>
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

    {{-- Coluna da Direita --}}
    <div class="space-y-6">
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

        {{-- Acessórios --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-plug-connected text-amber-500"></i>
                Acessórios Inclusos
            </h2>
            <div class="space-y-3">
                @forelse($licitacao->accessories as $accessory)
                <div class="flex items-center justify-between p-3 {{ $accessory->included ? 'bg-emerald-50 border-emerald-100' : 'bg-gray-50 border-gray-100' }} border rounded-xl">
                    <div class="flex items-center gap-3">
                        <i class="ph {{ $accessory->included ? 'ph-check-circle text-emerald-600' : 'ph-minus-circle text-slate-300' }} text-xl"></i>
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
    </div>
</div>
@endsection