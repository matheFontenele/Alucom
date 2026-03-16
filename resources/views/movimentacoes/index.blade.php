@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Histórico de Movimentações</h1>
        <button class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-700">
            Nova Movimentação
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-gray-600 text-sm">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Equipamento</th>
                    <th class="py-3 px-6 text-left">Tipo</th>
                    <th class="py-3 px-6 text-left">Origem/Destino</th>
                    <th class="py-3 px-6 text-left">Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($movimentacoes as $mov)
                    <tr>
                        <td class="py-4 px-6">#{{ $mov->id }}</td>
                        <td class="py-4 px-6 font-bold">{{ $mov->equipamento->nome }}</td>
                        <td class="py-4 px-6">{{ $mov->tipo }}</td>
                        <td class="py-4 px-6">
                            {{ $mov->cliente->nome ?? $mov->estoque->nome ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">Nenhuma movimentação registrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $movimentacoes->links() }}
        </div>
    </div>
</div>
@endsection