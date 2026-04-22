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
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-list-numbers text-red-500"></i>
                Itens Exigidos no Edital
            </h2>

            {{-- Formulário de Adição --}}
            <div class="bg-slate-50 rounded-2xl p-6 border border-dashed border-slate-300 mb-8">
                <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Novo Item Técnico</h3>

                {{-- Mudança para Flex para evitar que o grid limite o tamanho do input --}}
                <form action="{{ route('licitacoes-itens.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <input type="hidden" name="bidding_contract_id" value="{{ $licitacao->id }}">

                    <div class="flex-grow w-full">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Descrição do Item</label>
                        <input type="text" name="item_description" placeholder="Ex: Computador i5, 8GB RAM..."
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-red-500 transition text-sm" required>
                    </div>

                    <div class="w-full md:w-32">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Qtd</label>
                        {{-- CORREÇÃO CRÍTICA: Removido qualquer limitador. Type number com min 1 --}}
                        <input type="number"
                            name="quantity"
                            id="quantity"
                            placeholder="0"
                            min="1"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-red-500 transition text-sm font-bold text-center"
                            required>
                    </div>

                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded-lg transition flex items-center justify-center gap-2 text-sm shadow-lg shadow-red-200">
                            <i class="ph ph-plus-bold"></i> Add
                        </button>
                    </div>
                </form>
            </div>

            {{-- Listagem --}}
            <div class="space-y-4">
                @forelse($licitacao->items as $item)
                <div class="group flex justify-between items-center p-4 bg-white border border-gray-100 rounded-2xl hover:border-red-200 transition-all shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-red-50 text-red-600 font-black px-3 py-2 rounded-xl text-sm min-w-[60px] text-center">
                            {{ number_format($item->quantity, 0, ',', '.') }}x
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700 text-sm">{{ $item->item_description }}</h4>
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
    </div>
</div>
@endsection