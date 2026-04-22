@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded">CONTRATO</span>
                <span class="text-slate-500 font-bold text-sm tracking-widest">{{ $licitacao->contract_number }}</span>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $licitacao->uasg_organ }}</h1>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm text-right">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-tighter">Vigência Final</p>
            <p class="text-slate-700 font-bold">
                {{ $licitacao->end_date ? \Carbon\Carbon::parse($licitacao->end_date)->format('d/m/Y') : 'Não definida' }}
            </p>
        </div>
    </div>

    {{-- Cards Financeiros (Baseado na Planilha) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-[10px] font-black uppercase mb-1">Teto Contratual (Mês)</p>
            <h3 class="text-2xl font-black text-slate-800">R$ {{ number_format($licitacao->max_monthly_billing, 2, ',', '.') }}</h3>
        </div>
        <div class="bg-emerald-500 p-6 rounded-[2rem] shadow-lg shadow-emerald-100">
            <p class="text-white/70 text-[10px] font-black uppercase mb-1">Faturamento Atual (NF)</p>
            <h3 class="text-2xl font-black text-white">R$ {{ number_format($licitacao->current_billing, 2, ',', '.') }}</h3>
        </div>
        <div class="bg-orange-50 p-6 rounded-[2rem] border border-orange-100 shadow-sm">
            <p class="text-orange-600 text-[10px] font-black uppercase mb-1 text-right">Saldo Disponível</p>
            <h3 class="text-2xl font-black text-orange-800 text-right">R$ {{ number_format($licitacao->available_balance, 2, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Listagem por Lotes --}}
    <div class="space-y-8 mb-12">
        <div class="flex justify-between items-center px-4">
            <h2 class="text-xl font-black text-slate-800">Itens e Lotes</h2>
            <button onclick="document.getElementById('modal-item').classList.remove('hidden')" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 transition-all">
                + Adicionar Lotes
            </button>
        </div>

        @forelse($licitacao->items->groupBy('lote') as $lote => $itens)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-800 px-8 py-4 flex justify-between items-center">
                <h3 class="font-black text-white uppercase text-xs tracking-widest">Lote: {{ $lote ?? 'I' }}</h3>
                <span class="text-white/40 text-[10px] font-black">{{ count($itens) }} ITENS</span>
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] uppercase font-black text-slate-400 border-b border-gray-100">
                        <th class="px-8 py-4 w-1/3">Descrição do Edital</th>
                        <th class="px-8 py-4 text-center">Modelo Homologado</th>
                        <th class="px-8 py-4 text-center">Qtd</th>
                        <th class="px-8 py-4 text-right">R$ Unitário</th>
                        <th class="px-8 py-4 text-center">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($itens as $item)
                    <tr class="hover:bg-slate-50/30 transition">
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-slate-700 leading-tight">{{ $item->item_description }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($item->confirmed_model)
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                {{ $item->confirmed_model }}
                            </span>
                            @else
                            <span class="bg-slate-100 text-slate-400 px-3 py-1 rounded-full text-[10px] font-black uppercase italic">
                                Pendente
                            </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center font-black text-slate-600">{{ $item->contracted_quantity }}</td>
                        <td class="px-8 py-5 text-right font-bold text-slate-600 text-sm">
                            R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            <button onclick="openMatchModal({{ $item->id }}, '{{ $item->item_description }}')" class="text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase border border-red-100 transition">
                                Vincular Estoque
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @empty
        <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
            <p class="text-slate-400 font-bold italic">Nenhum lote cadastrado.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL 1: CADASTRO DE ITENS --}}
<div id="modal-item" class="hidden fixed inset-0 bg-slate-900/70 backdrop-blur-md flex items-center justify-center p-4 z-[100]">
    <div class="bg-white rounded-[3rem] shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-10 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-2xl font-black text-slate-800">Cadastrar Lotes e Itens</h3>
            <button onclick="document.getElementById('modal-item').classList.add('hidden')" class="text-slate-400 hover:text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('licitacoes-itens.store') }}" method="POST" class="flex flex-col overflow-hidden">
            @csrf
            <input type="hidden" name="bidding_contract_id" value="{{ $licitacao->id }}">
            <div class="p-10 overflow-y-auto space-y-4" id="wrapper-itens">
                <div class="grid grid-cols-12 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 item-row">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1">LOTE</label>
                        <input type="text" name="lote[]" class="w-full border-gray-200 rounded-xl text-sm" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1">TIPO</label>
                        <input type="text" name="item_type[]" class="w-full border-gray-200 rounded-xl text-sm">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-[10px] font-black text-slate-400 mb-1">DESCRIÇÃO TÉCNICA</label>
                        <input type="text" name="item_description[]" class="w-full border-gray-200 rounded-xl text-sm" required>
                    </div>
                    <div class="col-span-3 text-right">
                        <label class="block text-[10px] font-black text-slate-400 mb-1">VALOR UNIT. (R$)</label>
                        <input type="number" step="0.01" name="unit_price[]" class="w-full border-gray-200 rounded-xl text-sm text-right" required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-black text-slate-400 mb-1">QTD CONTRATO</label>
                        <input type="number" name="contracted_quantity[]" class="w-full border-gray-200 rounded-xl text-sm" required>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-slate-50 border-t border-gray-100 flex justify-between items-center">
                <button type="button" onclick="addItemRow()" class="text-slate-800 font-black text-xs uppercase tracking-widest">+ Outro Item</button>
                <div class="flex gap-3">
                    <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-red-700 transition">Salvar Tudo</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: HOMOLOGAÇÃO / MATCH DE ESTOQUE --}}
<div id="modal-match" class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[110] flex items-center justify-center p-6">
    <div class="bg-white rounded-[2rem] max-w-2xl w-full p-8 shadow-2xl">
        <h4 class="text-xl font-black text-slate-800 mb-2">Homologação de Equipamento</h4>
        <p class="text-sm text-slate-500 mb-6 italic" id="match-description-target"></p>

        <div class="space-y-4">
            <label class="text-[10px] font-black uppercase text-slate-400">Sugestões baseadas no estoque:</label>

            <div id="stock-suggestions-container" class="space-y-3">
                {{-- Aqui entrará o resultado da busca via JS --}}
                <div class="flex items-center justify-between p-4 border border-emerald-100 bg-emerald-50/50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-800 text-sm">Carregando sugestões...</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-100">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Modelo Manual / Customizado</label>
                <div class="flex gap-2">
                    <input type="text" id="manual-model-input" placeholder="Ex: HP ProBook 440 G8" class="flex-1 border-gray-200 rounded-xl text-sm">
                    <button onclick="confirmManualModel()" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase">Confirmar</button>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" onclick="document.getElementById('modal-match').classList.add('hidden')" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function addItemRow() {
        const wrapper = document.getElementById('wrapper-itens');
        const firstRow = wrapper.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        wrapper.appendChild(newRow);
    }

    let currentItemId = null;

    function openMatchModal(itemId, description) {
        currentItemId = itemId;
        document.getElementById('match-description-target').innerText = "Vincular equipamento para: " + description;
        document.getElementById('modal-match').classList.remove('hidden');

        const container = document.getElementById('stock-suggestions-container');
        container.innerHTML = '<p class="text-sm text-slate-400 p-4">Buscando no catálogo...</p>';

        // Faz a chamada real para o servidor
        fetch(`/api/sugestoes-estoque?q=${encodeURIComponent(description)}`)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p class="text-sm text-orange-500 p-4 font-bold">Nenhuma sugestão exata encontrada.</p>';
                    return;
                }

                data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = "flex items-center justify-between p-4 border border-slate-100 bg-slate-50 rounded-2xl hover:border-emerald-300 transition-all cursor-pointer group";
                    div.innerHTML = `
                    <div>
                        <p class="font-bold text-slate-800 text-sm">${item.nome}</p>
                        <p class="text-[10px] text-slate-400 uppercase">${item.categoria ? item.categoria.nome : 'Sem Categoria'}</p>
                    </div>
                    <button onclick="vincularModelo('${item.nome}')" class="bg-white border border-slate-200 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500">
                        SELECIONAR
                    </button>
                `;
                    container.appendChild(div);
                });
            })
            .catch(error => {
                container.innerHTML = '<p class="text-sm text-red-500 p-4">Erro ao carregar sugestões.</p>';
            });
    }

    function vincularModelo(nomeModelo) {
        document.getElementById('manual-model-input').value = nomeModelo;
        // Aqui você pode chamar o confirmManualModel() automaticamente se desejar
    }

    function confirmManualModel() {
        const modelName = document.getElementById('manual-model-input').value;
        if (!modelName) return alert('Digite um modelo');

        // Lógica de salvamento via AJAX aqui para o Controller
        alert('Modelo ' + modelName + ' homologado para o item ' + currentItemId);
        location.reload();
    }
</script>
@endsection