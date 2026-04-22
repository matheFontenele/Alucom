@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

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

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <p class="font-bold mb-1">Ops! Verifique os erros abaixo:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Equipamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Equipamento</label>
                <select id="equipamento_select" name="equipamento_id" class="w-full" required>
                    <option value="">Pesquise pelo Tombo ou Nome...</option>
                    @foreach($equipamentos as $e)
                    <option value="{{ $e->id }}"
                        data-localizacao="{{ $e->cliente->nome ?? $e->estoque->nome ?? 'Local Desconhecido' }}">
                        {{ $e->tombo }} - {{ $e->nome }} ({{ $e->status }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Ação</label>
                    <select name="tipo" id="tipo_select" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="Aluguel">Aluguel</option>
                        <option value="Devolução">Devolução</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Reservado">Reservado</option>
                    </select>
                </div>

                {{-- Situação --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Situação (Sub-status)</label>
                    <select name="situacao" id="situacao_select" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                    </select>
                </div>
            </div>

            {{-- Data --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Data da Movimentação</label>
                <input type="datetime-local" name="data_movimentacao" value="{{ date('Y-m-d\TH:i') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Seleção de Origem --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Origem</label>
                    <select name="origem" id="origem_select" class="w-full" required>
                        <option value="">Selecione a Origem</option>
                        <optgroup label="Estoques">
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->nome }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Clientes e Secretarias">
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->nome }}">
                                {{ $cliente->parent ? $cliente->parent->nome . ' > ' : '' }}{{ $cliente->nome }}
                            </option>
                            @endforeach
                        </optgroup>
                        <option value="Laboratório Interno">Laboratório Interno</option>
                    </select>
                </div>

                {{-- Seleção de Destino --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Destino</label>
                    <select name="destino" id="destino_select" class="w-full" required>
                        <option value="">Selecione o Destino</option>
                        <optgroup label="Clientes e Secretarias">
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->nome }}">
                                {{ $cliente->parent ? $cliente->parent->nome . ' > ' : '' }}{{ $cliente->nome }}
                            </option>
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

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    // Inicializar busca digitável nos selects
    const config = {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    };
    new TomSelect("#equipamento_select", config);
    const tsOrigem = new TomSelect("#origem_select", config);
    new TomSelect("#destino_select", config);

    // Lógica de Sub-status
    const situacoes = {
        'Aluguel': ['Aguardando Rota', 'Em Rota', 'No Cliente'],
        'Devolução': ['Aguardando Coleta', 'Em Rota', 'Recebido'],
        'Manutenção': ['Aguardando Retirada', 'Em Laboratório', 'Pronto'],
        'Reservado': ['Em Estoque', 'No Cliente'],
        'Disponível': ['Em Estoque']
    };

    const tipoSelect = document.getElementById('tipo_select');
    const situacaoSelect = document.getElementById('situacao_select');

    tipoSelect.addEventListener('change', function() {
        const tipo = this.value;
        situacaoSelect.innerHTML = '';
        if (situacoes[tipo]) {
            situacoes[tipo].forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.innerHTML = s;
                situacaoSelect.appendChild(opt);
            });
        }
    });
    tipoSelect.dispatchEvent(new Event('change'));

    // Auto-preenchimento da Origem
    document.getElementById('equipamento_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const localAtual = selectedOption.getAttribute('data-localizacao');
        if (localAtual) {
            tsOrigem.setValue(localAtual);
        }
    });
</script>
@endsection