@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="ph ph-clipboard-text text-blue-900"></i>
            Requisições de Equipamentos & Insumos
        </h1>
        <a href="{{ route('requisicoes.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-800 flex items-center gap-2">
            <i class="ph ph-plus-circle"></i> Nova Solicitação
        </a>
    </div>

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
                        {{-- ID --}}
                        <td class="px-4 py-4 text-center font-bold text-gray-900">
                            #{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Ofício e Solicitante --}}
                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800">{{ $req->oficio }}</div>
                            <div class="text-xs text-gray-400 italic">Por: {{ $req->solicitante }}</div>
                        </td>

                        {{-- Cliente e Local --}}
                        <td class="px-4 py-4">
                            <div class="text-gray-800 font-medium">{{ $req->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $req->cidade }} - {{ $req->estado }}</div>
                        </td>

                        {{-- Item e Tipo --}}
                        <td class="px-4 py-4">
                            <div class="text-blue-900 font-bold">{{ $req->quantidade }}x {{ $req->item_descricao }}</div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 border {{ $req->tipo_solicitacao == 'Substituição' ? 'text-amber-600 border-amber-200' : 'text-green-600 border-green-200' }}">
                                {{ $req->tipo_solicitacao }}
                            </span>
                        </td>

                        {{-- Status de Envio (Logística) --}}
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $req->envio }}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-1">
                                {{ $req->previsao_envio ? \Carbon\Carbon::parse($req->previsao_envio)->format('d/m/Y') : 'Sem data' }}
                            </div>
                        </td>

                        {{-- NOVA COLUNA DE STATUS DO PROCESSO --}}
                        <td class="px-4 py-4 text-center">
                            @php
                            $statusStyle = match($req->situacao) {
                            'Pendente' => 'bg-gray-100 text-gray-600 border-gray-200',
                            'Atendida' => 'bg-green-100 text-green-700 border-green-200',
                            'Parcialmente' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'Solicitado Compra' => 'bg-orange-100 text-orange-700 border-orange-200',
                            default => 'bg-gray-100 text-gray-600 border-gray-200'
                            };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase border {{ $statusStyle }}">
                                {{ $req->situacao }}
                            </span>
                        </td>

                        {{-- Ações Simplificadas --}}
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end items-center gap-4">
                                <a href="{{ route('requisicoes.show', $req->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver Detalhes">
                                    <i class="ph ph-eye text-2xl"></i>
                                </a>
                                <a href="{{ route('requisicoes.edit', $req->id) }}" class="text-gray-400 hover:text-amber-600 transition-colors" title="Editar Requisição">
                                    <i class="ph ph-pencil-simple text-2xl"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 italic">
                            Nenhuma requisição encontrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requisicoes->hasPages())
        <div class="px-4 py-3 bg-gray-50 border-t">
            {{ $requisicoes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection