@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter">Rede de Técnicos</h1>
            <p class="text-slate-500 font-medium">Gerencie prestadores de serviço e parceiros regionais.</p>
        </div>
        <a href="{{ route('tecnicos.create') }}" class="bg-blue-900 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-red-200 hover:scale-105 transition-all flex items-center gap-2">
            <i class="ph ph-plus-circle text-lg"></i> CADASTRAR TÉCNICO
        </a>
    </div>
    {{-- Barra de Filtros --}}
    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-200 mb-8">
        <form action="{{ route('tecnicos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            {{-- Busca por Texto --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Busca Geral</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou CNPJ..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 pl-10 font-bold outline-none focus:ring-2 focus:ring-red-500 text-sm transition-all">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ph ph-magnifying-glass font-bold"></i>
                    </div>
                </div>
            </div>

            {{-- Filtro de Região --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Região</label>
                <input type="text" name="regiao" value="{{ request('regiao') }}" placeholder="Ex: Belém..."
                    class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 font-bold outline-none focus:ring-2 focus:ring-red-500 text-sm">
            </div>

            {{-- Filtro de Especialidade --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Especialidade</label>
                <select name="tipo" class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 font-bold outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    <option value="">Todas</option>
                    <option value="Impressoras" {{ request('tipo') == 'Impressoras' ? 'selected' : '' }}>Impressoras</option>
                    <option value="Informatica" {{ request('tipo') == 'Informatica' ? 'selected' : '' }}>Informática</option>
                    <option value="Ambos" {{ request('tipo') == 'Ambos' ? 'selected' : '' }}>Ambos</option>
                </select>
            </div>

            {{-- Botões --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white p-2.5 rounded-xl font-black hover:bg-slate-700 transition-all text-xs uppercase tracking-widest">
                    Filtrar
                </button>
                <a href="{{ route('tecnicos.index') }}" class="bg-slate-100 text-slate-400 p-2.5 rounded-xl hover:bg-slate-200 transition-all" title="Limpar Filtros">
                    <i class="ph ph-arrow-counter-clockwise font-bold"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Técnico / CNPJ</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Especialidade</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Região de Atuação</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($tecnicos as $tecnico)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-black text-slate-700">{{ $tecnico->nome }}</p>
                        <p class="text-xs text-slate-400 font-bold">{{ $tecnico->cnpj ?? 'CPF NÃO INFORMADO' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase {{ $tecnico->tipo == 'Impressoras' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                            {{ $tecnico->tipo }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-slate-500 font-bold text-sm">
                            <i class="ph ph-map-pin text-red-500"></i> {{ $tecnico->regiao }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('tecnicos.show', $tecnico->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-800 hover:text-white transition-all">
                                <i class="ph ph-eye font-bold"></i>
                            </a>
                            <a href="{{ route('tecnicos.edit', $tecnico->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-amber-500 hover:text-white transition-all">
                                <i class="ph ph-pencil-simple font-bold"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection