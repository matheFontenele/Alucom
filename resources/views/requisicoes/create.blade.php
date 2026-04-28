@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="container mx-auto p-6" x-data="requisicaoRapida()">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200">

        {{-- Cabeçalho --}}
        <div class="bg-blue-900 p-5 flex justify-between items-center">
            <h2 class="text-white text-xl font-bold flex items-center gap-3">
                <i class="ph ph-file-plus text-2xl"></i> Nova Requisição em Lote
            </h2>
            <span class="text-blue-200 text-sm font-medium uppercase tracking-widest">Modo Planilha</span>
        </div>

        <form action="{{ route('requisicoes.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            {{-- SEÇÃO 1: DADOS FIXOS (CABECALHO DA REQUISIÇÃO) --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Ofício</label>
                        <input type="text" name="oficio" placeholder="Ex: 0336/2025" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Estoque de Origem</label>
                        <select name="estoque_id" id="estoque_select" class="w-full border-blue-200 bg-white rounded-xl focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Selecione o Estoque</option>
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- NOVO CAMPO: TIPO DE ENVIO (Obrigatório para evitar erro 23502) --}}
                    <div>
                        <label class="block text-xs font-black uppercase text-blue-600 mb-1">Tipo de Envio *</label>
                        <select name="envio" class="w-full border-blue-200 bg-white rounded-xl focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Selecione...</option>
                            <option value="Rota">Rota</option>
                            <option value="Coleta">Coleta</option>
                            <option value="Transportadora">Transportadora</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Previsão de Envio</label>
                        <input type="date" name="previsao_envio" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Cliente</label>
                        <select name="cliente_id" id="cliente_select" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Selecione o Cliente</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}"
                                data-cidade="{{ $cliente->cidade }}"
                                data-estado="{{ $cliente->estado }}"
                                data-etiqueta="{{ $cliente->contrato }}">
                                {{ $cliente->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Cidade / UF</label>
                        <div class="flex gap-2">
                            <input type="text" id="cidade_input" name="cidade" readonly class="w-full bg-gray-100 border-gray-200 rounded-xl text-gray-500 text-sm">
                            <input type="text" id="estado_input" name="estado" readonly class="w-16 bg-gray-100 border-gray-200 rounded-xl text-gray-500 text-sm text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Etiqueta Principal</label>
                        <select name="etiqueta" id="etiqueta_select" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="Alucom">Alucom</option>
                            <option value="Moreia">Moreia</option>
                            <option value="IP">IP</option>
                            <option value="ZapLoc">ZapLoc</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 2: TABELA DINÂMICA (ITENS) --}}
            <div class="pt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-blue-900 font-black uppercase text-sm tracking-widest flex items-center gap-2">
                        <i class="ph ph-list-numbers text-lg"></i> Itens da Requisição
                    </h3>
                    <button type="button" @click="addRow()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700 transition flex items-center gap-2 shadow-lg">
                        <i class="ph ph-plus-circle text-lg"></i> ADICIONAR ITEM
                    </button>
                </div>

                <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-blue-50 border-b border-gray-200 text-[10px] font-black uppercase text-blue-900">
                            <tr>
                                <th class="px-4 py-3 border-r">Descrição / Item (Escrito)</th>
                                <th class="px-4 py-3 border-r w-24 text-center">Qtd</th>
                                <th class="px-4 py-3 border-r w-40">Categoria</th>
                                <th class="px-4 py-3 border-r w-40">Tipo</th>
                                <th class="px-4 py-3 border-r">Patrimônio Substituto</th>
                                <th class="px-4 py-3 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(row, index) in rows" :key="row.id">
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    {{-- Descrição --}}
                                    <td class="p-0 border-r">
                                        <input type="text" :name="'item_nome['+index+']'" required
                                            class="w-full border-none focus:ring-0 p-3 text-sm placeholder-gray-300 bg-transparent"
                                            placeholder="Ex: Toner Brother TN3472...">
                                    </td>
                                    {{-- Qtd --}}
                                    <td class="p-0 border-r text-center">
                                        <input type="number" :name="'item_qtd['+index+']'" value="1" min="1"
                                            class="w-full border-none focus:ring-0 p-3 text-sm text-center bg-transparent">
                                    </td>
                                    {{-- Categoria --}}
                                    <td class="p-0 border-r">
                                        <select :name="'item_categoria['+index+']'" class="w-full border-none focus:ring-0 p-3 text-xs bg-transparent font-medium">
                                            <option value="Insumo">Insumo</option>
                                            <option value="Equipamento">Equipamento</option>
                                        </select>
                                    </td>
                                    {{-- Tipo --}}
                                    <td class="p-0 border-r">
                                        <select :name="'item_tipo['+index+']'" x-model="row.tipo" class="w-full border-none focus:ring-0 p-3 text-xs bg-transparent font-medium">
                                            <option value="Novo">Novo</option>
                                            <option value="Substituição">Substituição</option>
                                        </select>
                                    </td>
                                    {{-- Patrimônio --}}
                                    <td class="p-0 border-r">
                                        <input type="text" :name="'item_patrimonio['+index+']'"
                                            :required="row.tipo === 'Substituição'"
                                            :disabled="row.tipo === 'Novo'"
                                            :class="row.tipo === 'Novo' ? 'bg-gray-50 cursor-not-allowed italic text-gray-300' : 'bg-white text-blue-600 font-bold'"
                                            class="w-full border-none focus:ring-0 p-3 text-sm placeholder-gray-200 bg-transparent"
                                            placeholder="Nº Patrimônio">
                                    </td>
                                    {{-- Ações --}}
                                    <td class="p-2 text-center">
                                        <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="text-red-400 hover:text-red-600 transition">
                                            <i class="ph ph-trash-simple text-xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rodapé / Submit --}}
            <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                <div class="text-gray-400 text-xs italic">
                    <i class="ph ph-info"></i> Cada linha da tabela gerará um registro individual no banco de dados.
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('requisicoes.index') }}" class="px-8 py-3 text-gray-400 font-bold hover:text-gray-600 transition uppercase text-xs">Cancelar</a>
                    <button type="submit" class="bg-blue-900 text-white px-12 py-3 rounded-xl font-black hover:bg-blue-800 transition transform hover:-translate-y-1 shadow-xl active:scale-95 uppercase text-sm tracking-widest">
                        Processar Lote
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function requisicaoRapida() {
        return {
            rows: [{
                id: Date.now(),
                tipo: 'Novo'
            }],
            addRow() {
                this.rows.push({
                    id: Date.now(),
                    tipo: 'Novo'
                });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            }
        }
    }

    document.getElementById('cliente_select').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('cidade_input').value = option.getAttribute('data-cidade') || '';
        document.getElementById('estado_input').value = option.getAttribute('data-estado') || '';

        const contrato = option.getAttribute('data-etiqueta');
        const selectEtiqueta = document.getElementById('etiqueta_select');

        if (contrato) {
            const valor = contrato.trim().toLowerCase();
            for (let i = 0; i < selectEtiqueta.options.length; i++) {
                if (selectEtiqueta.options[i].value.toLowerCase() === valor) {
                    selectEtiqueta.selectedIndex = i;
                    break;
                }
            }
        }
    });
</script>
@endsection