@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- Topo: Título e Busca --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Gerenciamento de Clientes</h1>
            <p class="text-slate-500 text-sm">Cadastre e visualize seus clientes ativos.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <form action="{{ route('clientes.index') }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar nome ou CNPJ..."
                        class="w-full rounded-2xl border-slate-200 bg-white p-2.5 pl-10 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all text-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ph ph-magnifying-glass font-bold"></i>
                    </div>
                </div>
            </form>

            <a href="{{ route('clientes.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-red-200 flex items-center justify-center gap-2">
                <i class="ph ph-plus-circle text-lg"></i>
                Novo Cliente
            </a>
        </div>
    </div>
    {{-- Barra de Filtros --}}
    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-200 mb-8">
        <form action="{{ route('clientes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Busca Geral</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou CNPJ..." class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 font-bold text-sm outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Estado (UF)</label>
                <select name="estado" class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 font-bold text-sm outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Todos</option>
                    <option value="PA" {{ request('estado') == 'PA' ? 'selected' : '' }}>Pará</option>
                    <option value="DF" {{ request('estado') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                    <option value="CE" {{ request('estado') == 'CE' ? 'selected' : '' }}>Ceará</option>
                    {{-- Adicione outros conforme necessário --}}
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Contrato</label>
                <select name="contrato" class="w-full rounded-xl border-slate-200 bg-slate-50 p-2.5 font-bold text-sm outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">TODOS</option>
                    <option value="ALUCOM" {{ request('contrato') == 'ALUCOM' ? 'selected' : '' }}>ALUCOM</option>
                    <option value="MOREIA" {{ request('contrato') == 'MOREIA' ? 'selected' : '' }}>MOREIA</option>
                    <option value="IP" {{ request('contrato') == 'IP' ? 'selected' : '' }}>IP</option>
                    <option value="ZAPLOC" {{ request('contrato') == 'ZAPLOC' ? 'selected' : '' }}>ZAPLOC</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white p-2.5 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-700">Filtrar</button>
                <a href="{{ route('clientes.index') }}" class="bg-slate-100 text-slate-400 p-2.5 rounded-xl hover:bg-slate-200"><i class="ph ph-arrow-counter-clockwise font-bold"></i></a>
            </div>
        </form>
    </div>
    {{-- Tabela de Clientes --}}
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Razão Social</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">CNPJ</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center">Contrato</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Localização</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm text-slate-500">#{{ $cliente->id }}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $cliente->nome }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $cliente->cnpj }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                            // Define as classes de cor baseadas no contrato
                            $colorClasses = match($cliente->contrato) {
                            'Alucom' => 'bg-red-50 text-red-600 border-red-100',
                            'Moreia' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'Zaplok' => 'bg-green-50 text-emerald-600 border-emerald-100',
                            'IP' => 'bg-blue-50 text-blue-600 border-blue-100',
                            default => 'bg-slate-50 text-slate-600 border-slate-100',
                            };
                            @endphp

                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase border {{ $colorClasses }}">
                                {{ $cliente->contrato ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            <span class="inline-flex items-center gap-1 text-sm">
                                <i class="ph ph-map-pin text-red-500"></i>
                                {{ $cliente->cidade }} - {{ $cliente->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-1">
                            {{-- Visualizar --}}
                            <a href="{{ route('clientes.show', $cliente->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Ver Detalhes">
                                <i class="ph ph-eye text-xl"></i>
                            </a>

                            {{-- Editar (Página Separada) --}}
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Editar Cliente">
                                <i class="ph ph-pencil-simple text-xl"></i>
                            </a>

                            {{-- Excluir --}}
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este cliente?');" class="inline">
                                @csrf @method('DELETE')
                                <button class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Excluir">
                                    <i class="ph ph-trash text-xl"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                            @if(request('search'))
                            Nenhum cliente encontrado para "{{ request('search') }}".
                            @else
                            Nenhum cliente cadastrado.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection