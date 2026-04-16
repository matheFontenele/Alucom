@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">
    {{-- Exibição de Erros e Sucesso --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm">
        <p class="font-black uppercase text-xs mb-2">Ops! Algo deu errado:</p>
        <ul class="list-disc ml-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Cabeçalho e Voltar --}}
    <div class="mb-8">
        <a href="{{ route('estoques.index') }}" class="text-slate-400 hover:text-slate-600 font-bold flex items-center gap-2 mb-4 transition">
            <i class="ph ph-arrow-left"></i> Voltar para Gestão de Estoques
        </a>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $estoque->nome }}</h1>
                <p class="text-gray-500 flex items-center gap-2 mt-2 font-medium">
                    <i class="ph ph-map-pin-line text-blue-500"></i> {{ $estoque->localizacao }}
                </p>

                <div class="flex flex-wrap gap-4 mt-6">
                    {{-- Botão Equipamentos (Redireciona para Massa) --}}
                    <a href="{{ route('equipamentos.mass_entry', ['estoque_id' => $estoque->id]) }}"
                        class="bg-slate-900 hover:bg-slate-800 text-white text-xs px-6 py-4 rounded-xl font-bold flex items-center gap-3 transition shadow-lg">
                        <i class="ph ph-desktop text-xl text-blue-400"></i>
                        <div class="text-left">
                            <span class="block">Novo Equipamento</span>
                            <span class="text-[9px] text-slate-400 uppercase tracking-tighter">Entrada com Patrimônio</span>
                        </div>
                    </a>

                    {{-- Botão Insumos (Redireciona para Massa) --}}
                    <a href="{{ route('insumos.mass_entry', ['estoque_id' => $estoque->id]) }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-6 py-4 rounded-xl font-bold flex items-center gap-3 transition shadow-lg shadow-emerald-100">
                        <i class="ph ph-drop text-xl"></i>
                        <div class="text-left">
                            <span class="block">Novo Insumo</span>
                            <span class="text-[9px] text-emerald-200 uppercase tracking-tighter">Entrada por Quantidade</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Card de Resumo --}}
            <div class="bg-slate-900 text-white p-4 rounded-2xl shadow-xl flex items-center gap-4 min-w-[200px]">
                <div class="bg-blue-600 p-3 rounded-xl">
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
        <form action="{{ route('estoques.show', $estoque->id) }}" method="GET" class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nome ou modelo..."
                    class="pl-10 pr-4 py-2 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 w-full md:w-80 shadow-sm outline-none">
            </div>

            <select name="status" class="border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 shadow-sm outline-none px-4">
                <option value="">Todos os Status</option>
                <option value="Disponivel" {{ request('status') == 'Disponivel' ? 'selected' : '' }}>Disponível</option>
                <option value="Alugado" {{ request('status') == 'Alugado' ? 'selected' : '' }}>Alugado</option>
                <option value="Manutenção" {{ request('status') == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
            </select>

            <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-900 transition">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('estoques.show', $estoque->id) }}" class="text-gray-400 hover:text-red-600 flex items-center text-sm font-bold">
                Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- Tabela --}}
    <div class="bg-white border border-gray-200 rounded-b-2xl shadow-sm overflow-hidden mb-12">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Equipamento / Modelo</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Quantidade</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($equipamentosAgrupados as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                                <i class="ph ph-package text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900">{{ $item->nome }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">Modelo Unificado</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase
                            {{ $item->status == 'Disponivel' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-slate-800 text-lg">
                        {{ $item->total }} <span class="text-[10px] text-slate-400 font-bold">UNID.</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('estoques.detalhes-item', [$estoque->id, $item->nome]) }}"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <i class="ph ph-eye text-xl"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <i class="ph ph-magnifying-glass text-5xl text-slate-200 mb-3"></i>
                        <p class="text-slate-500 font-bold">Nenhum item encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection