@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <i class="ph ph-books text-blue-600"></i>
                Catálogo de Ativos
            </h1>
            <p class="text-slate-500 mt-1 font-medium">Especificações técnicas globais por categoria de produto.</p>
        </div>

        <a href="{{ route('catalogos.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-200 hover:-translate-y-1">
            <i class="ph ph-plus-circle text-xl"></i> Novo Modelo
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 animate-pulse">
            <i class="ph ph-check-circle text-2xl"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Barra de Filtros --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-10">
        <form action="{{ route('catalogos.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-4">
            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Pesquisar por modelo ou fabricante..."
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none border border-slate-100 font-medium">
            </div>

            <select name="categoria_id" class="w-full md:w-64 px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                <option value="">Todas as Categorias</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nome }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                Filtrar Resultados
            </button>
            
            @if(request()->anyFilled(['search', 'categoria_id']))
                <a href="{{ route('catalogos.index') }}" class="p-3 text-slate-400 hover:text-red-500 transition-colors" title="Limpar Filtros">
                    <i class="ph ph-arrows-counter-clockwise text-xl"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Grid de Categorias --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($itens as $categoria => $modelos)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col transition-all hover:shadow-md">
            <div class="bg-slate-50/80 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <i class="ph ph-tag-chevron-right text-xl font-bold"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-slate-800 uppercase tracking-tight text-sm">{{ $categoria }}</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Modelos Disponíveis</p>
                    </div>
                </div>
                <span class="bg-white border border-slate-200 text-slate-600 px-3 py-1 rounded-full text-[11px] font-black shadow-sm">
                    {{ $modelos->count() }} itens
                </span>
            </div>

            <div class="p-4 flex-1">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-50">
                        @foreach($modelos as $modelo)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-2">
                                <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $modelo->nome }}</div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">{{ $modelo->fabricante }}</div>
                                
                                {{-- Especificações Rápidas --}}
                                @if($modelo->processador)
                                    <div class="mt-1 flex gap-2 text-[10px] text-slate-500 font-medium italic">
                                        <span>{{ $modelo->processador }}</span> • <span>{{ $modelo->memoria }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-2 text-right">
                                <div class="flex flex-wrap justify-end gap-1.5 items-center">
                                    @if($modelo->voltagem)
                                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px] font-bold">{{ $modelo->voltagem }}</span>
                                    @endif

                                    @if($modelo->cor)
                                        <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $modelo->cor }}</span>
                                            <div class="w-2.5 h-2.5 rounded-full border border-white shadow-sm" style="background-color: {{ $modelo->cor_hex ?? '#ccc' }}"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
            <i class="ph ph-folder-open text-6xl text-slate-300"></i>
            <p class="text-slate-500 font-bold mt-4">Nenhum modelo encontrado.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection