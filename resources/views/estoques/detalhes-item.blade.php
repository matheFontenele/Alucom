@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- Cabeçalho --}}
    <div class="mb-8">
        {{-- Link de Voltar Dinâmico --}}
        <a href="{{ route('estoques.show', $estoque->id) }}" class="text-slate-400 hover:text-slate-600 font-bold flex items-center gap-2 mb-4 transition">
            <i class="ph ph-arrow-left"></i> Voltar para {{ $estoque->nome }}
        </a>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Listagem Individual</h1>
                <p class="text-gray-500 italic flex items-center gap-2 font-medium">
                    <i class="ph ph-package text-blue-500"></i> {{ $nome }}
                </p>
            </div>

            {{-- Badge de Quantidade Filtrada --}}
            <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-4">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <i class="ph ph-list-numbers text-xl"></i>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block">Total deste Grupo</span>
                    <div class="text-2xl font-black">{{ $itens->count() }} <span class="text-xs font-medium text-slate-400">unid.</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Busca e Filtros --}}
    <div class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap gap-3">
            <div class="relative flex-1 min-w-[300px]">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por Tombo ou Nº de Série..."
                    class="pl-10 pr-4 py-2 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 w-full shadow-sm outline-none">
            </div>

            <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-900 transition flex items-center gap-2">
                <i class="ph ph-funnel"></i> Filtrar
            </button>

            @if(request('search'))
            <a href="{{ url()->current() }}" class="text-red-500 hover:bg-red-50 px-4 py-2 rounded-xl font-bold text-sm transition flex items-center">
                Limpar Busca
            </a>
            @endif
        </form>
    </div>

    {{-- Tabela de Itens --}}
    <div class="bg-white border border-gray-200 rounded-b-2xl shadow-sm overflow-hidden mb-10">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Equipamento</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tombo / Patrimônio</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nº de Série</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Observações / Cor</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Entrada</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($itens as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="text-slate-400 bg-slate-100 p-2 rounded-lg">
                                @if($item->tipo == 'insumo')
                                <i class="ph ph-drop-half text-lg"></i>
                                @else
                                <i class="ph ph-desktop text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800">{{ $item->nome }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $item->subcategoria->nome ?? 'S/Sub' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold {{ $item->tombo ? 'text-blue-600 bg-blue-50 px-2 py-1 rounded-md' : 'text-slate-300 italic' }}">
                            {{ $item->tombo ?? 'Sem Tombo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                        {{ $item->serial ?? '---' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($item->cor)
                                <div class="w-3 h-3 rounded-full border border-slate-300 shadow-sm"
                                     style="background-color: {{ $item->cor_hex ?? '#ccc' }};"
                                     title="Cor: {{ $item->cor }}"></div>
                                <span class="text-xs font-bold text-slate-600">{{ $item->cor }}</span>
                            @endif

                            @if($item->observacoes)
                                <span class="text-xs text-slate-500 {{ $item->cor ? 'border-l pl-2 border-slate-200' : '' }}">
                                    {{ $item->observacoes }}
                                </span>
                            @endif

                            @if(!$item->cor && !$item->observacoes)
                                <span class="text-slate-300 text-xs italic">Nenhuma observação</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-black rounded-lg uppercase 
                        {{ $item->status == 'Disponivel' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-slate-500 font-medium">
                        {{ $item->data_movimentacao ? \Carbon\Carbon::parse($item->data_movimentacao)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="ph ph-ghost text-5xl text-slate-200 mb-3"></i>
                        <p class="text-slate-500 font-bold">Nenhum item individual encontrado neste grupo.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection