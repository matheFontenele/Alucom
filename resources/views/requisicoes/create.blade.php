@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-blue-900 p-4">
            <h2 class="text-white text-lg font-bold flex items-center gap-2">
                <i class="ph ph-file-plus"></i> Nova Requisição de Material
            </h2>
        </div>

        <form action="{{ route('requisicoes.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Linha 1: Ofício e Solicitante --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700">Ofício</label>
                    <input type="text" name="oficio" placeholder="Ex: 0336/2025" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Solicitante</label>
                    <input type="text" name="solicitante" value="{{ auth()->user()->name ?? 'Usuário Logado' }}" readonly class="w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            {{-- Linha 2: Cliente e Localização Automática --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700">Cliente</label>
                    <select name="cliente_id" id="cliente_select" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        <option value="">Selecione o Cliente</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" data-cidade="{{ $cliente->cidade }}" data-estado="{{ $cliente->estado }}">
                            {{ $cliente->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Cidade</label>
                    <input type="text" name="cidade" id="cidade_input" readonly class="w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Estado</label>
                    <input type="text" name="estado" id="estado_input" readonly class="w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            {{-- Linha 3: Item do Catálogo e Quantidade --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Equipamento | Insumo (Catálogo)</label>
                    <select name="catalogo_id" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        @foreach($catalogo as $item)
                        <option value="{{ $item->id }}">{{ $item->nome }} - {{ $item->fabricante }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Quantidade</label>
                    <input type="number" name="quantidade" min="1" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>
            </div>

            {{-- Linha 4: Tipo de Solicitação e Patrimônio --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-blue-50 p-4 rounded-lg">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Solicitação</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_solicitacao" value="Novo" checked class="text-blue-900">
                            <span class="ml-2">Novo Item</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_solicitacao" value="Substituição" id="radio_substituicao" class="text-blue-900">
                            <span class="ml-2">Substituição</span>
                        </label>
                    </div>
                </div>
                <div id="campo_patrimonio" class="hidden">
                    <label class="block text-sm font-bold text-gray-700">Patrimônio (A ser substituído)</label>
                    <input type="text" name="patrimonio_substituido" class="w-full border-blue-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('requisicoes.index') }}" class="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="bg-blue-900 text-white px-8 py-2 rounded-lg font-bold hover:bg-blue-800 transition shadow-lg">
                    Salvar Requisição
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Lógica de Cidade/Estado Automática
    document.getElementById('cliente_select').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('cidade_input').value = option.getAttribute('data-cidade') || '';
        document.getElementById('estado_input').value = option.getAttribute('data-estado') || '';
    });

    // Mostrar/Esconder Patrimônio
    document.querySelectorAll('input[name="tipo_solicitacao"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const divPatrimonio = document.getElementById('campo_patrimonio');
            if (this.value === 'Substituição') {
                divPatrimonio.classList.remove('hidden');
            } else {
                divPatrimonio.classList.add('hidden');
            }
        });
    });
</script>
@endsection