@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200">
        {{-- Cabeçalho --}}
        <div class="bg-blue-900 p-5 flex justify-between items-center">
            <h2 class="text-white text-xl font-bold flex items-center gap-3">
                <i class="ph ph-file-plus text-2xl"></i> Nova Requisição de Material
            </h2>
            <span class="text-blue-200 text-sm font-medium">ID: Automático</span>
        </div>

        <form action="{{ route('requisicoes.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            {{-- Seção 1: Informações Básicas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Ofício</label>
                    <input type="text" name="oficio" placeholder="Ex: 0336/2025 ou Sem Ofício" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Solicitante</label>
                    <input type="text" value="{{ auth()->user()->name ?? 'Usuário Logado' }}" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Data da Solicitação</label>
                    <input type="text" value="{{ date('d/m/Y') }}" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                </div>
            </div>

            {{-- Seção 2: Localização e Logística --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-gray-100">
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Cliente</label>
                    <select name="cliente_id" id="cliente_select" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione o Cliente</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            data-cidade="{{ $cliente->cidade }}"
                            data-estado="{{ $cliente->estado }}"
                            data-etiqueta="{{ $cliente->contrato }}"> {{-- Mapeado para o campo 'contrato' --}}
                            {{ $cliente->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Cidade</label>
                    <input type="text" name="cidade" id="cidade_input" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Estado</label>
                    <input type="text" name="estado" id="estado_input" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-500">
                </div>
            </div>

            {{-- Seção 3: Detalhes de Envio --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Previsão de Envio</label>
                    <input type="date" name="previsao_envio" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Meio de Envio</label>
                    <select name="envio" class="w-full border-gray-200 rounded-xl">
                        <option value="Coleta">Coleta</option>
                        <option value="Rota">Rota</option>
                        <option value="Transportadora">Transportadora</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Número da NFE</label>
                    <input type="text" name="nfe" placeholder="Nº ou Sem NF" class="w-full border-gray-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Etiqueta</label>
                    <select name="etiqueta" id="etiqueta_select" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                        <option value="Alucom">Alucom</option>
                        <option value="Moreia">Moreia</option>
                        <option value="IP">IP</option>
                        <option value="ZapLoc">ZapLoc</option>
                    </select>
                </div>
            </div>

            {{-- Seção 4: Item e Quantidade --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Equipamento | Insumo (Disponível em Estoque)</label>
                    <select name="catalogo_id" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione o Item</option>
                        @foreach($catalogo as $item)
                        <option value="{{ $item->id }}" {{ $item->equipamentos_count <= 0 ? 'disabled' : '' }}>
                            {{ $item->nome }} - {{ $item->fabricante }}
                            ({{ $item->equipamentos_count }} unidades em estoque)
                        </option>
                        @endforeach
                    </select>
                    @if($catalogo->where('equipamentos_count', '>', 0)->count() == 0)
                    <p class="text-red-500 text-xs mt-1">Aviso: Não há itens com estoque disponível.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Quantidade Solicitada</label>
                    <input type="number" name="quantidade" min="1" placeholder="0" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            {{-- Seção 5: Tipo de Solicitação e Patrimônio --}}
            <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-blue-900 mb-3">Tipo de Solicitação</label>
                    <div class="flex gap-8">
                        <label class="inline-flex items-center group cursor-pointer">
                            <input type="radio" name="tipo_solicitacao" value="Novo" checked class="w-5 h-5 text-blue-900 focus:ring-blue-900">
                            <span class="ml-3 text-gray-700 font-medium group-hover:text-blue-900 transition">Novo Item</span>
                        </label>
                        <label class="inline-flex items-center group cursor-pointer">
                            <input type="radio" name="tipo_solicitacao" value="Substituição" id="radio_substituicao" class="w-5 h-5 text-blue-900 focus:ring-blue-900">
                            <span class="ml-3 text-gray-700 font-medium group-hover:text-blue-900 transition">Substituição</span>
                        </label>
                    </div>
                </div>

                <div id="campo_patrimonio" class="hidden transform transition-all duration-300 scale-95 opacity-0">
                    <label class="block text-xs font-black uppercase text-blue-600 mb-1">Patrimônio do Equipamento a ser substituído</label>
                    <input type="text" name="patrimonio_substituido" placeholder="Digite o número do patrimônio" class="w-full border-blue-200 rounded-xl shadow-inner focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex justify-end items-center gap-4 pt-8 border-t border-gray-100">
                <a href="{{ route('requisicoes.index') }}" class="px-8 py-3 text-gray-400 font-bold hover:text-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-900 text-white px-12 py-3 rounded-xl font-black hover:bg-blue-800 transition transform hover:-translate-y-1 shadow-xl active:scale-95">
                    SALVAR REQUISIÇÃO
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Atualização Automática de Cidade, Estado e Etiqueta (Contrato)
    document.getElementById('cliente_select').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];

        // Preenche Cidade e Estado
        document.getElementById('cidade_input').value = option.getAttribute('data-cidade') || '';
        document.getElementById('estado_input').value = option.getAttribute('data-estado') || '';

        // Preenche a Etiqueta com base no campo Contrato do cliente
        const contratoSugerido = option.getAttribute('data-etiqueta');
        const selectEtiqueta = document.getElementById('etiqueta_select');

        if (contratoSugerido) {
            // Normaliza para comparação (remove espaços e ignora case)
            const valorParaComparar = contratoSugerido.trim().toLowerCase();

            let encontrou = false;
            for (let i = 0; i < selectEtiqueta.options.length; i++) {
                if (selectEtiqueta.options[i].value.toLowerCase() === valorParaComparar) {
                    selectEtiqueta.selectedIndex = i;
                    encontrou = true;
                    break;
                }
            }

            // Feedback visual se uma correspondência for encontrada
            if (encontrou) {
                selectEtiqueta.classList.add('ring-2', 'ring-blue-400', 'border-blue-400');
                setTimeout(() => {
                    selectEtiqueta.classList.remove('ring-2', 'ring-blue-400', 'border-blue-400');
                }, 1000);
            }
        }
    });

    // 2. Lógica Dinâmica para o Campo de Patrimônio
    document.querySelectorAll('input[name="tipo_solicitacao"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const divPatrimonio = document.getElementById('campo_patrimonio');
            if (this.value === 'Substituição') {
                divPatrimonio.classList.remove('hidden');
                setTimeout(() => divPatrimonio.classList.remove('scale-95', 'opacity-0'), 10);
            } else {
                divPatrimonio.classList.add('scale-95', 'opacity-0');
                setTimeout(() => divPatrimonio.classList.add('hidden'), 300);
            }
        });
    });
</script>
@endsection