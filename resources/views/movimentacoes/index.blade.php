@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    .table-container {
        overflow: visible !important;
    }

    table {
        border-collapse: separate;
    }

    td.acoes-cell {
        overflow: visible !important;
        position: relative;
    }
</style>

<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Histórico de Movimentações</h1>
        <a href="{{ route('movimentacoes.create') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-700 transition flex items-center gap-2">
            <i class="ph ph-plus-circle text-lg"></i>
            Nova Movimentação
        </a>
    </div>

    {{-- Alertas de Sucesso ou Erro --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-lg shadow-sm border-l-4 border-emerald-500">
        {{ session('success') }}
    </div>
    @endif

    {{-- Container da Tabela com overflow visible para não cortar o dropdown --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 table-container">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase text-[11px] tracking-wider font-semibold">
                    <th class="py-4 px-6 text-left">ID</th>
                    <th class="py-4 px-6 text-left">Equipamento</th>
                    <th class="py-4 px-6 text-left">Tipo</th>
                    <th class="py-4 px-6 text-left">Origem / Destino</th>
                    <th class="py-4 px-6 text-left">Data</th>
                    <th class="py-4 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @forelse($movimentacoes as $mov)
                @php
                // Lógica dinâmica de cores baseada na empresa/origem
                $empresaKey = strtolower($mov->origem);
                $corEmpresa = '#D32F2F'; // Padrão Alucom (Vermelho)

                if (str_contains($empresaKey, 'moreia')) $corEmpresa = '#FF8C00'; // Laranja
                if (str_contains($empresaKey, 'ip')) $corEmpresa = '#0000FF'; // Azul
                if (str_contains($empresaKey, 'zaploc')) $corEmpresa = '#2E8B57'; // Verde
                @endphp

                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-400 font-mono">#{{ $mov->id }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">
                        {{ $mov->equipamento->nome ?? 'N/A' }}
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                            {{ $mov->tipo == 'Aluguel' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $mov->tipo == 'Devolução' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $mov->tipo == 'Manutenção' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $mov->tipo == 'Liberação' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $mov->tipo == 'Reservado' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $mov->tipo == 'Substituição' ? 'bg-orange-100 text-orange-700' : '' }}">
                            {{ $mov->tipo }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div class="flex items-center gap-2">
                            {{-- Indicador visual da cor da empresa --}}
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $corEmpresa }}"></span>
                            <span class="text-gray-500">{{ $mov->origem }}</span>
                            <i class="ph ph-arrow-right text-gray-300"></i>
                            <span class="text-slate-800 font-medium">{{ $mov->destino }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $mov->data_movimentacao->format('d/m/Y H:i') }}
                    </td>

                    {{-- COLUNA DE AÇÕES --}}
                    <td class="py-4 px-6 text-center acoes-cell" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="p-2 rounded-full hover:bg-gray-100 transition text-gray-500">
                            <i class="ph ph-dots-three-outline-vertical text-xl"></i>
                        </button>

                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-12 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-[9999] overflow-hidden text-left"
                            style="display: none; top: 10px;">
                            <div class="py-1 bg-white">
                                <a href="{{ route('movimentacoes.show', $mov->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50">
                                    <i class="ph ph-eye mr-2 text-blue-500 text-lg"></i> Visualizar detalhes
                                </a>
                                <a href="{{ route('movimentacoes.edit', $mov->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50">
                                    <i class="ph ph-pencil-simple mr-2 text-amber-500 text-lg"></i> Editar
                                </a>
                                <a href="{{ route('movimentacoes.protocolo', $mov->id) }}" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50">
                                    <i class="ph ph-file-pdf mr-2 text-red-600 text-lg"></i> Emitir Protocolo
                                </a>

                                <a href="{{ route('movimentacoes.etiqueta', $mov->id) }}" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50">
                                    <i class="ph ph-file-pdf mr-2 text-red-600 text-lg"></i> Gerar Etiqueta
                                </a>

                                <form action="{{ route('movimentacoes.destroy', $mov->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <i class="ph ph-trash mr-2 text-lg"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
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