@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Histórico de Movimentações</h1>
        <a href="{{ route('movimentacoes.create') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-700 transition flex items-center gap-2">
            <i class="ph ph-plus-circle text-lg"></i>
            Nova Movimentação
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase text-[11px] tracking-wider font-semibold">
                    <th class="py-4 px-6 text-left">ID</th>
                    <th class="py-4 px-6 text-left">Equipamento</th>
                    <th class="py-4 px-6 text-left">Tipo</th>
                    <th class="py-4 px-6 text-left">Origem / Destino</th>
                    <th class="py-4 px-6 text-left">Data</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @forelse($movimentacoes as $mov)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-sm text-gray-400 font-mono">#{{ $mov->id }}</td>
                        <td class="py-4 px-6 font-bold text-slate-800">{{ $mov->equipamento->nome }}</td>
                        <td class="py-4 px-6">
                            {{-- Badge colorida para o tipo --}}
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                {{ $mov->tipo == 'Aluguel' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $mov->tipo == 'Devolução' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $mov->tipo == 'Manutenção' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $mov->tipo == 'Liberação' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $mov->tipo == 'Reservado' ? 'bg-purple-100 text-purple-700' : '' }}">
                                {{ $mov->tipo }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm">
                            {{-- Exibição baseada nas novas colunas string --}}
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500">{{ $mov->origem }}</span>
                                <i class="ph ph-arrow-right text-gray-300"></i>
                                <span class="text-slate-800 font-medium">{{ $mov->destino }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500">
                            {{ $mov->data_movimentacao->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="ph ph-calendar-x text-4xl text-gray-200 mb-2"></i>
                                <p class="text-gray-400 italic">Nenhuma movimentação registrada.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($movimentacoes->hasPages())
            <div class="p-4 border-t border-gray-50">
                {{ $movimentacoes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection