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

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-lg font-bold text-sm border-l-4 border-emerald-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase text-[11px] tracking-wider font-semibold">
                    <th class="py-4 px-6 text-left">ID</th>
                    <th class="py-4 px-6 text-left">Equipamento</th>
                    <th class="py-4 px-6 text-left">Tipo</th>
                    <th class="py-4 px-6 text-left">Origem / Destino</th>
                    <th class="py-4 px-6 text-left">Data</th>
                    <th class="py-4 px-6 text-right">Ações</th> {{-- Nova Coluna --}}
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @forelse($movimentacoes as $mov)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-sm text-gray-400 font-mono">#{{ $mov->id }}</td>
                        <td class="py-4 px-6 font-bold text-slate-800">{{ $mov->equipamento->nome ?? 'Item Removido' }}</td>
                        <td class="py-4 px-6">
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
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500">{{ $mov->origem }}</span>
                                <i class="ph ph-arrow-right text-gray-300"></i>
                                <span class="text-slate-800 font-medium">{{ $mov->destino }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500">
                            {{ $mov->data_movimentacao->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            {{-- Botão Deletar --}}
                            <form action="{{ route('movimentacoes.destroy', $mov->id) }}" method="POST" onsubmit="return confirm('Excluir este registro de histórico permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-600 transition">
                                    <i class="ph ph-trash text-xl"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- ... bloco empty se mantém igual ... --}}
                @endforelse
            </tbody>
        </table>
        {{-- ... links de paginação se mantém iguais ... --}}
    </div>
</div>
@endsection