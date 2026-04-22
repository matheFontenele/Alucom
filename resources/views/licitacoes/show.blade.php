@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <span class="text-red-600 font-bold text-sm uppercase tracking-widest">Contrato {{ $licitacao->contract_number }}</span>
        <h1 class="text-3xl font-black text-slate-800">{{ $licitacao->uasg_organ }}</h1>
    </div>
    <div class="text-right">
        <p class="text-slate-400 text-xs font-bold uppercase">Vigência Final</p>
        <p class="text-slate-700 font-bold">
            {{ $licitacao->end_date ? \Carbon\Carbon::parse($licitacao->end_date)->format('d/m/Y') : 'Não definida' }}
        </p>
    </div>
</div>

{{-- CARDS DE RESUMO FINANCEIRO --}}
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
        <button onclick="document.getElementById('modal-item').classList.remove('hidden')" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg hover:bg-slate-700 transition">
            + Adicionar Itens (Lotes)
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
            @forelse($licitacao->items as $item)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-8 py-4 text-xs font-bold text-slate-500">{{ $item->lote }} / {{ $item->item_type }}</td>
                <td class="px-8 py-4">
                    <p class="text-sm font-bold text-slate-700">{{ $item->item_description }}</p>
                </td>
                <td class="px-8 py-4 text-center font-bold text-slate-600">{{ $item->contracted_quantity }}</td>
                <td class="px-8 py-4 text-center">
                    <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg font-black text-xs">
                        {{ $item->delivered_quantity }}
                    </span>
                </td>
                <td class="px-8 py-4 text-right text-sm text-slate-600">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                <td class="px-8 py-4 text-right font-black text-slate-800 text-sm">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-8 py-12 text-center text-slate-400 italic">Nenhum item cadastrado. Clique em "+ Adicionar Item".</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL PARA ADICIONAR MÚLTIPLOS ITENS --}}
<div id="modal-item" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-800">Adicionar Itens ao Contrato</h3>
                <p class="text-sm text-slate-500">Preencha as informações dos lotes e itens técnicos.</p>
            </div>
            <button onclick="document.getElementById('modal-item').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('licitacoes-itens.store') }}" method="POST" class="flex flex-col overflow-hidden">
            @csrf
            <input type="hidden" name="bidding_contract_id" value="{{ $licitacao->id }}">

            <div class="p-8 overflow-y-auto space-y-4" id="wrapper-itens">
                {{-- Linha de Item (Template) --}}
                <div class="grid grid-cols-12 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 relative item-row">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Lote</label>
                        <input type="text" name="lote[]" placeholder="LOTE I" class="w-full border-gray-200 rounded-xl text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Tipo</label>
                        <input type="text" name="item_type[]" placeholder="TIPO II" class="w-full border-gray-200 rounded-xl text-sm">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Descrição do Item</label>
                        <input type="text" name="item_description[]" placeholder="Ex: Notebook I5 16GB" class="w-full border-gray-200 rounded-xl text-sm" required>
                    </div>
                    <div class="col-span-3 text-right">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Valor Unit. (R$)</label>
                        <input type="number" step="0.01" name="unit_price[]" placeholder="0,00" class="w-full border-gray-200 rounded-xl text-sm text-right" required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Qtd Contrato</label>
                        <input type="number" name="contracted_quantity[]" class="w-full border-gray-200 rounded-xl text-sm" required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Qtd Entregue</label>
                        <input type="number" name="delivered_quantity[]" value="0" class="w-full border-gray-200 rounded-xl text-sm">
                    </div>
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-gray-100 flex justify-between items-center">
                <button type="button" onclick="addItemRow()" class="text-red-600 font-bold text-sm hover:text-red-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Adicionar Mais Itens
                </button>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal-item').classList.add('hidden')" class="px-6 py-2 text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="bg-red-600 text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition">
                        Salvar Todos os Itens
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function addItemRow() {
        const wrapper = document.getElementById('wrapper-itens');
        const firstRow = wrapper.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        // Limpa os valores dos inputs clonados
        newRow.querySelectorAll('input').forEach(input => {
            if (input.name !== 'delivered_quantity[]') {
                input.value = '';
            } else {
                input.value = '0';
            }
        });

        wrapper.appendChild(newRow);
    }
</script>
@endsection