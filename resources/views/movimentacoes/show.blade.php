@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-blue-900 p-4 flex justify-between items-center">
            <h2 class="text-white font-bold">Detalhes da Movimentação #{{ $movimentacao->id }}</h2>
            <a href="{{ route('movimentacoes.index') }}" class="text-blue-200 hover:text-white">Voltar</a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-black uppercase text-gray-400">Informações do Equipamento</h3>
                <p class="text-gray-800"><strong>Patrimônio:</strong> {{ $movimentacao->equipamento->patrimonio }}</p>
                <p class="text-gray-800"><strong>Modelo:</strong> {{ $movimentacao->equipamento->catalogo->nome }}</p>
                <p class="text-gray-800"><strong>S/N:</strong> {{ $movimentacao->equipamento->num_serie }}</p>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase text-gray-400">Destino / Cliente</h3>
                <p class="text-gray-800"><strong>Cliente:</strong> {{ $movimentacao->requisicao->cliente->nome ?? 'N/D' }}</p>
                <p class="text-gray-800"><strong>Cidade:</strong> {{ $movimentacao->requisicao->cliente->cidade ?? 'N/D' }}</p>
            </div>

            <div class="md:col-span-2 pt-4 border-t">
                <h3 class="text-xs font-black uppercase text-gray-400">Logística</h3>
                <p class="text-gray-800"><strong>Data:</strong> {{ \Carbon\Carbon::parse($movimentacao->data_movimentacao)->format('d/m/Y H:i') }}</p>
                <p class="text-gray-800"><strong>Tipo:</strong> {{ $movimentacao->tipo }}</p>
                <p class="text-gray-800"><strong>Emitido por:</strong> {{ $movimentacao->user->name ?? 'Sistema' }}</p>
            </div>
        </div>
        
        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('movimentacoes.protocolo', $movimentacao->id) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">
                GERAR PDF
            </a>
        </div>
    </div>
</div>
@endsection