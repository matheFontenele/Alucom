@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="ph ph-clipboard-text text-blue-900"></i>
            Requisições de Equipamentos & Insumos
        </h1>
        <a href="{{ route('requisicoes.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-800 flex items-center gap-2 transition-all">
            <i class="ph ph-plus-circle"></i> Nova Solicitação
        </a>
    </div>

    {{-- BARRA DE FILTROS --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('requisicoes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">

            {{-- Filtro: Cliente/Local --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Cliente / Local</label>
                <input type="text" name="cliente" value="{{ request('cliente') }}"
                    placeholder="Ex: Fundo Municipal..."
                    class="w-full bg-slate-50 border-gray-200 rounded-lg text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Filtro: Item --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Item / Descrição</label>
                <input type="text" name="item" value="{{ request('item') }}"
                    placeholder="Ex: Toner..."
                    class="w-full bg-slate-50 border-gray-200 rounded-lg text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Filtro: Status --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Status</label>
                <select name="status" class="w-full bg-slate-50 border-gray-200 rounded-lg text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos os Status</option>
                    <option value="Pendente" {{ request('status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="Atendida" {{ request('status') == 'Atendida' ? 'selected' : '' }}>Atendida</option>
                    <option value="Parcialmente" {{ request('status') == 'Parcialmente' ? 'selected' : '' }}>Parcialmente</option>
                    <option value="Solicitado Compra" {{ request('status') == 'Solicitado Compra' ? 'selected' : '' }}>Solicitado Compra</option>
                    <option value="Finalizada" {{ request('status') == 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
                </select>
            </div>

            {{-- Botões de Ação --}}
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] uppercase py-3 rounded-lg transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-magnifying-glass text-base"></i> Filtrar
                </button>
                <a href="{{ route('requisicoes.index') }}" class="px-4 bg-slate-100 hover:bg-slate-200 text-slate-500 font-black text-[10px] uppercase py-3 rounded-lg transition-all flex items-center justify-center" title="Limpar Filtros">
                    <i class="ph ph-arrows-counter-clockwise text-base"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- TABELA DE RESULTADOS --}}
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-center">ID</th>
                        <th class="px-4 py-3">Ofício / Solicitante</th>
                        <th class="px-4 py-3">Cliente / Local</th>
                        <th class="px-4 py-3">Item / Qtd</th>
                        <th class="px-4 py-3 text-center">Logística</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requisicoes as $req)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 text-center font-bold text-gray-900">
                            #{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800">{{ $req->oficio }}</div>
                            <div class="text-xs text-gray-400 italic">Por: {{ $req->solicitante }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-gray-800 font-medium">{{ $req->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $req->cidade }} - {{ $req->estado }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-blue-900 font-bold">{{ $req->quantidade }}x {{ $req->item_descricao }}</div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 border {{ $req->tipo_solicitacao == 'Substituição' ? 'text-amber-600 border-amber-200' : 'text-green-600 border-green-200' }}">
                                {{ $req->tipo_solicitacao }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $req->envio }}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-1">
                                Prev: {{ $req->previsao_envio ? \Carbon\Carbon::parse($req->previsao_envio)->format('d/m/Y') : '--' }}
                            </div>
                        </td>

                        <td class="px-4 py-4 text-center">
                            @php
                            $statusClasses = match($req->situacao) {
                            'Pendente' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'Atendida' => 'bg-green-50 text-green-700 border-green-200',
                            'Parcialmente' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Solicitado Compra' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Finalizada' => 'bg-gray-100 text-gray-600 border-gray-200',
                            default => 'bg-gray-50 text-gray-500 border-gray-200'
                            };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase border {{ $statusClasses }}">
                                {{ $req->situacao }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('requisicoes.show', $req->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver Detalhes">
                                    <i class="ph ph-eye text-2xl"></i>
                                </a>
                                <a href="{{ route('requisicoes.edit', $req->id) }}" class="text-gray-400 hover:text-amber-600 transition-colors" title="Editar Requisição">
                                    <i class="ph ph-pencil-simple text-2xl"></i>
                                </a>
                                @if($req->situacao === 'Pendente')
                                <a href="{{ route('requisicoes.separacao', $req->id) }}"
                                    class="p-2 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition-all flex items-center justify-center shadow-sm"
                                    title="Separar Material">
                                    <i class="ph ph-package-open text-xl"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <i class="ph ph-magnifying-glass text-5xl text-gray-200 block mb-3 mx-auto"></i>
                            <span class="text-gray-400 italic">Nenhum resultado encontrado para os filtros aplicados.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINAÇÃO --}}
        @if($requisicoes->hasPages())
        <div class="px-4 py-3 bg-gray-50 border-t">
            {{ $requisicoes->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection