@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- Topo --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clientes.index') }}" class="p-3 bg-white rounded-2xl border border-slate-200 text-slate-400 hover:text-slate-600 shadow-sm transition-all">
            <i class="ph ph-arrow-left font-bold"></i>
        </a>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-black text-slate-800">{{ $cliente->nome }}</h1>
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-slate-400 hover:text-blue-600 transition-all" title="Editar informações">
                <i class="ph ph-note-pencil text-xl"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Card de Dados Cadastrais --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-200">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">Dados Cadastrais</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase">CNPJ</p>
                        <p class="font-bold text-slate-700">{{ $cliente->cnpj }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Contrato Ativo</p>
                        @php
                        $colorClasses = match($cliente->contrato) {
                        'Alucom' => 'bg-red-50 text-red-600 border-red-100',
                        'Moreia' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'Zaploc' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'IP' => 'bg-blue-50 text-blue-600 border-blue-100',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-lg font-black text-xs uppercase border {{ $colorClasses }}">
                            {{ $cliente->contrato ?? 'Não Informado' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase">Localização</p>
                        <p class="font-bold text-slate-700 leading-tight">{{ $cliente->endereco }}<br>{{ $cliente->cidade }} - {{ $cliente->estado }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Tempo de SLA</p>
                        <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-600 space-y-1">
                            <p><strong>Atendimento:</strong> {{ $cliente->sla['Atendimento'] ?? 'N/D' }}h</p>
                            <p><strong>Insumos:</strong> {{ $cliente->sla['Insumos'] ?? 'N/D' }}h</p>
                            <p><strong>Substituição:</strong> {{ $cliente->sla['Substituição'] ?? 'N/D' }}h</p>
                            <p><strong>Remanejamento:</strong> {{ $cliente->sla['Remanejamento'] ?? 'N/D' }}h</p>
                            <p class="pt-1 font-bold text-slate-800 italic">Insumos: {{ $cliente->sla['Tipo'] ?? 'N/D' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção de Unidades Vinculadas --}}
        <div class="md:col-span-2">
            @if($cliente->tipo == 'ministerio')
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <h2 class="text-xl font-black text-slate-800 tracking-tight">Unidades Vinculadas</h2>

                {{-- Barra de Filtro de Unidades --}}
                <form action="{{ route('clientes.show', $cliente->id) }}" method="GET" class="flex gap-2 w-full md:w-auto">
                    <div class="relative flex-1">
                        <input type="text" name="search_unidade" value="{{ request('search_unidade') }}"
                            placeholder="Buscar unidade ou cidade..."
                            class="w-full md:w-64 rounded-xl border-slate-200 bg-white p-2 pl-9 text-xs font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
                        <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400 font-bold text-sm"></i>
                    </div>
                    <button type="submit" class="bg-slate-800 text-white p-2 rounded-xl hover:bg-slate-700 transition-all">
                        <i class="ph ph-funnel font-bold"></i>
                    </button>
                    <a href="{{ route('clientes.create', ['parent_id' => $cliente->id]) }}" class="bg-red-600 text-white text-[10px] px-4 py-2 rounded-xl font-black uppercase tracking-widest hover:bg-red-700 transition-all shadow-md shadow-red-100 flex items-center gap-2">
                        <i class="ph ph-plus-circle font-bold"></i> Unidade
                    </a>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nome da Unidade</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cidade/UF</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($unidades as $unidade) {{-- Use a variável $unidades filtrada do Controller --}}
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700 text-sm">{{ $unidade->nome }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold">
                                    <i class="ph ph-map-pin text-red-500"></i>
                                    {{ $unidade->cidade }} - {{ $unidade->estado }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('clientes.show', $unidade->id) }}" class="text-red-600 font-black text-[10px] uppercase hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all">Ver Detalhes</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                                {{ request('search_unidade') ? 'Nenhuma unidade encontrada para esta busca.' : 'Nenhuma unidade cadastrada.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection