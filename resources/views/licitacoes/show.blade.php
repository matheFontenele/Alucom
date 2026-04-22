@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <span class="text-red-600 font-bold text-sm uppercase tracking-widest">Contrato {{ $licitacao->contract_number }}</span>
        <h1 class="text-3xl font-black text-slate-800">{{ $licitacao->uasg_organ }}</h1>
    </div>
    <div class="text-right">
        <p class="text-slate-400 text-xs font-bold uppercase">Vigência Final</p>
        <p class="text-slate-700 font-bold">{{ $licitacao->end_date ? $licitacao->end_date->format('d/m/Y') : 'Não definida' }}</p>
    </div>
</div>

{{-- CARDS DE RESUMO FINANCEIRO (Baseado na sua planilha) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <p class="text-slate-400 text-xs font-black uppercase mb-1">Teto Contratual</p>
        <h3 class="text-2xl font-black text-slate-800">R$ {{ number_format($licitacao->max_monthly_billing, 2, ',', '.') }}</h3>
    </div>
    <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100">
        <p class="text-emerald-600 text-xs font-black uppercase mb-1">Faturamento Atual (NF)</p>
        <h3 class="text-2xl font-black text-emerald-800">R$ {{ number_format($licitacao->current_billing, 2, ',', '.') }}</h3>
    </div>
    <div class="bg-orange-50 p-6 rounded-3xl border border-orange-100">
        <p class="text-orange-600 text-xs font-black uppercase mb-1">Saldo Disponível</p>
        <h3 class="text-2xl font-black text-orange-800">R$ {{ number_format($licitacao->available_balance, 2, ',', '.') }}</h3>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-slate-800">Itens e Quantidades</h2>
        <button onclick="document.getElementById('modal-item').classList.toggle('hidden')" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-bold">
            + Adicionar Item
        </button>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-[10px] uppercase font-black text-slate-400">
                <th class="px-8 py-4">Lote/Tipo</th>
                <th class="px-8 py-4">Descrição</th>
                <th class="px-8 py-4 text-center">Qtd Contrato</th>
                <th class="px-8 py-4 text-center">Qtd Entregue</th>
                <th class="px-8 py-4 text-right">R$ Unitário</th>
                <th class="px-8 py-4 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($licitacao->items as $item)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-8 py-4 text-xs font-bold text-slate-500">{{ $item->lote }} / {{ $item->item_type }}</td>
                <td class="px-8 py-4">
                    <p class="text-sm font-bold text-slate-700">{{ Str::limit($item->item_description, 50) }}</p>
                </td>
                <td class="px-8 py-4 text-center font-bold text-slate-600">{{ $item->contract_quantity }}</td>
                <td class="px-8 py-4 text-center">
                    <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg font-black text-xs">
                        {{ $item->delivered_quantity }}
                    </span>
                </td>
                <td class="px-8 py-4 text-right text-sm text-slate-600">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                <td class="px-8 py-4 text-right font-black text-slate-800 text-sm">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- MODAL SIMPLES PARA ADICIONAR ITEM --}}
<div id="modal-item" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-2xl w-full">
        <h3 class="text-xl font-bold mb-6">Novo Item Técnico</h3>
        <form action="{{ route('licitacoes-itens.store') }}" method="POST">
            @csrf
            <input type="hidden" name="bidding_contract_id" value="{{ $licitacao->id }}">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-400">Descrição</label>
                    <input type="text" name="item_description" class="w-full border-gray-200 rounded-xl" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400">Lote</label>
                    <input type="text" name="lote" placeholder="LOTE I" class="w-full border-gray-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400">Tipo</label>
                    <input type="text" name="item_type" placeholder="TIPO II" class="w-full border-gray-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400">Qtd Contrato</label>
                    <input type="number" name="contract_quantity" class="w-full border-gray-200 rounded-xl" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400">Qtd Instalada (Faturamento)</label>
                    <input type="number" name="delivered_quantity" class="w-full border-gray-200 rounded-xl" value="0">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-400">Valor Unitário Mensal (R$)</label>
                    <input type="number" step="0.01" name="unit_price" class="w-full border-gray-200 rounded-xl" required>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-xl font-bold">Salvar Item</button>
                <button type="button" onclick="document.getElementById('modal-item').classList.toggle('hidden')" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-xl font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>
@endsection