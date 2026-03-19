@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- Cabeçalho e Voltar --}}
    <div class="mb-8">
        <a href="{{ route('estoques.index') }}" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-2 mb-4 transition">
            <i class="ph ph-arrow-left font-bold"></i> Voltar para Gestão de Estoques
        </a>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $estoque->nome }}</h1>
                <p class="text-gray-500 flex items-center gap-2 mt-2">
                    <i class="ph ph-map-pin-line text-red-500"></i> {{ $estoque->localizacao }}
                </p>
            </div>

            {{-- Card de Resumo de Quantidade --}}
            <div class="bg-slate-900 text-white p-4 rounded-2xl shadow-xl flex items-center gap-4 min-w-[200px]">
                <div class="bg-red-600 p-3 rounded-xl">
                    <i class="ph ph-stack text-2xl"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black">{{ $equipamentosAgrupados->sum('total') }}</span>
                    <span class="text-xs uppercase font-bold text-slate-400">Total de Itens</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('estoques.show', $estoque->id) }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            {{-- Busca por Nome/Modelo --}}
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nome ou modelo..."
                    class="pl-10 pr-4 py-2 border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 w-full md:w-80 shadow-sm">
            </div>

            {{-- Filtro de Status --}}
            <select name="status" class="border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 shadow-sm">
                <option value="">Todos os Status</option>
                <option value="Disponivel" {{ request('status') == 'Disponivel' ? 'selected' : '' }}>Disponível</option>
                <option value="Alugado" {{ request('status') == 'Alugado' ? 'selected' : '' }}>Alugado</option>
                <option value="Manutenção" {{ request('status') == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                <option value="Reservado" {{ request('status') == 'Reservado' ? 'selected' : '' }}>Reservado</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-red-700 transition shadow-md">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('estoques.show', $estoque->id) }}" class="text-gray-400 hover:text-red-600 flex items-center text-sm">
                Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- Tabela de Equipamentos Agrupados --}}
    <div class="bg-white border border-gray-200 rounded-b-2xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Equipamento / Modelo</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Quantidade em Estoque</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($equipamentosAgrupados as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                                <i class="ph ph-desktop text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900">{{ $item->nome }}</div>
                                <div class="text-xs text-gray-400">Modelo unificado</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter
                            {{ $item->status == 'Disponivel' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xl font-black text-slate-800">{{ $item->total }}</span>
                        <span class="text-gray-400 text-xs ml-1">unid.</span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <button class="text-slate-400 hover:text-red-600 transition" title="Ver itens individuais">
                            <a href="{{ route('estoques.detalhes-item', [$estoque->id, $item->nome]) }}"
                                class="text-slate-400 hover:text-red-600 transition" title="Ver itens individuais">
                                <i class="ph ph-eye text-xl"></i>
                            </a> </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="ph ph-magnifying-glass text-5xl text-gray-200 mb-4"></i>
                            <span class="text-gray-500 font-medium">Nenhum equipamento encontrado com esses filtros.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection