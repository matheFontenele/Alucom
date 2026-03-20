@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- Cabeçalho --}}
    <div class="mb-8">
        <a href="{{ route('estoques.show', $estoque->id) }}" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-2 mb-4 transition">
            <i class="ph ph-arrow-left font-bold"></i> Voltar para {{ $estoque->nome }}
        </a>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Listagem Individual</h1>
                <p class="text-gray-500 italic flex items-center gap-2">
                    <i class="ph ph-package text-red-500"></i> {{ $nome }}
                </p>
            </div>

            {{-- Badge de Quantidade Filtrada --}}
            <div class="bg-slate-100 px-4 py-2 rounded-xl border border-slate-200">
                <span class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total do Grupo</span>
                <div class="text-2xl font-black text-slate-800">{{ $itens->count() }} <span class="text-sm font-medium">unid.</span></div>
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
                    class="pl-10 pr-4 py-2 border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 w-full shadow-sm">
            </div>

            <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-900 transition flex items-center gap-2">
                <i class="ph ph-funnel"></i> Filtrar
            </button>

            @if(request('search'))
            <a href="{{ url()->current() }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-gray-200 transition">
                Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- Tabela de Itens --}}
    <div class="bg-white border border-gray-200 rounded-b-2xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Equipamento</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tombo / Patrimônio</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nº de Série</th>
                    {{-- NOVA COLUNA --}}
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Observações / Cor</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Entrada</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($itens as $item)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Ícone Dinâmico baseado no tipo --}}
                            <div class="text-slate-400">
                                @if($item->tipo == 'insumo')
                                <i class="ph ph-drop-half text-lg"></i>
                                @else
                                <i class="ph ph-desktop text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800">{{ $item->nome }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $item->subcategoria->nome ?? 'S/Sub' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold {{ $item->tombo ? 'text-red-600 bg-red-50/30' : 'text-slate-300' }}">
                        {{ $item->tombo ?? 'S/T (Insumo)' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                        {{ $item->serial }}
                    </td>

                    {{-- NOVA COLUNA: Lógica de Cor e Observação --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($item->cor)
                            {{-- Círculo Colorido usando o Acessor do Model --}}
                            <div class="w-3 h-3 rounded-full border border-slate-300 shadow-sm"
                                style="background-color: {{ $item->cor_hex }};"
                                title="Cor: {{ $item->cor }}"></div>
                            <span class="text-xs font-bold text-slate-600">{{ $item->cor }}</span>
                            @endif

                            @if($item->observacoes)
                            <span class="text-xs text-slate-500 {{ $item->cor ? 'border-l pl-2 border-slate-200' : '' }}">
                                {{ $item->observacoes }}
                            </span>
                            @endif

                            @if(!$item->cor && !$item->observacoes)
                            <span class="text-slate-300 text-xs">---</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-[10px] font-black rounded-lg uppercase 
                        {{ $item->status == 'Disponivel' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ $item->data_movimentacao ? \Carbon\Carbon::parse($item->data_movimentacao)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @empty
                {{-- ... manter o @empty original ... --}}
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection