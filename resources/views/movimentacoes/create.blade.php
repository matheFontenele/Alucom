@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-blue-900 p-4">
            <h2 class="text-white text-lg font-bold flex items-center gap-2">
                <i class="ph ph-arrows-left-right"></i>
                Registrar Nova Movimentação
            </h2>
        </div>

        <form action="{{ route('movimentacoes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            {{-- Equipamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Equipamento</label>
                <select id="equipamento_select" name="equipamento_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Selecione o Equipamento</option>
                    @foreach($equipamentos as $e)
                    <option value="{{ $e->id }}"
                        data-localizacao="{{ $e->cliente->nome ?? $e->estoque->nome ?? 'Local Desconhecido' }}">
                        {{ $e->tombo }} - {{ $e->nome }} ({{ $e->status }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Ação</label>
                    <select name="tipo" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="Aluguel">Aluguel</option>
                        <option value="Devolução">Devolução</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Liberação">Liberação</option>
                        <option value="Reservado">Reservado</option>
                        <option value="Substituição">Substituição</option>
                    </select>
                </div>

                {{-- Data --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Data da Movimentação</label>
                    <input type="datetime-local" name="data_movimentacao" value="{{ date('Y-m-d\TH:i') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Seleção de Origem --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Origem</label>
                    <select name="origem" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Selecione a Origem</option>
                        <optgroup label="Estoques">
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->nome }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Clientes">
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->nome }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </optgroup>
                        <option value="Laboratório Interno">Laboratório Interno</option>
                    </select>
                </div>

                {{-- Seleção de Destino --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Destino</label>
                    <select name="destino" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Selecione o Destino</option>
                        <optgroup label="Clientes">
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->nome }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Estoques">
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->nome }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </optgroup>
                        <option value="Laboratório Interno">Laboratório Interno</option>
                    </select>
                </div>
            </div>

            {{-- Observação --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Observações</label>
                <textarea name="observacao" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Detalhes opcionais..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('movimentacoes.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-900 rounded-lg hover:bg-blue-800 shadow-md transition">
                    Confirmar Movimentação
                </button>
            </div>
        </form>
    </div>
</div>

<!--Script de auto preenchimento-->
<script>
document.getElementById('equipamento_select').addEventListener('change', function() {
    // Obtém a localização atual do atributo data-localizacao
    const selectedOption = this.options[this.selectedIndex];
    const localAtual = selectedOption.getAttribute('data-localizacao');
    
    // Localiza o campo de Origem (Select)
    const selectOrigem = document.querySelector('select[name="origem"]');
    
    if (localAtual && selectOrigem) {
        // Itera sobre as opções da Origem para encontrar o texto correspondente
        for (let i = 0; i < selectOrigem.options.length; i++) {
            if (selectOrigem.options[i].text === localAtual) {
                selectOrigem.selectedIndex = i;
                break;
            }
        }
    }
});
</script>
@endsection