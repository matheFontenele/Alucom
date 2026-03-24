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
                        <th class="px-4 py-3 text-center">Status Envio</th>
                        <th class="px-4 py-3 text-center">Separação</th>
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
                            <div class="text-gray-800 font-medium">{{ $req->cliente->nome }}</div>
                            <div class="text-xs text-gray-500">{{ $req->cidade }} - {{ $req->estado }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-blue-900 font-bold">{{ $req->quantidade }}x {{ $req->item->modelo }}</div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 border {{ $req->tipo_solicitacao == 'Substituição' ? 'text-amber-600 border-amber-200' : 'text-green-600 border-green-200' }}">
                                {{ $req->tipo_solicitacao }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $req->envio }}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-1">Prev: {{ \Carbon\Carbon::parse($req->previsao_envio)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($req->quantidade_separada)
                                <span class="flex items-center justify-center gap-1 text-green-600 font-bold">
                                    <i class="ph ph-check-circle"></i> {{ $req->quantidade_separada }}/{{ $req->quantidade }}
                                </span>
                            @else
                                <span class="text-gray-300 italic text-xs">Pendente</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                {{-- Botões de Visualizar e Editar --}}
                                <a href="{{ route('requisicoes.show', $req->id) }}" class="text-gray-400 hover:text-blue-600" title="Ver Detalhes">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
                                <a href="{{ route('requisicoes.edit', $req->id) }}" class="text-gray-400 hover:text-amber-600" title="Editar Requisição">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </a>

                                {{-- Botão em Destaque: Separação --}}
                                <a href="{{ route('requisicoes.separacao', $req->id) }}" 
                                   class="flex items-center gap-2 bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 shadow-sm transition-all transform hover:scale-105">
                                    <i class="ph ph-package text-sm"></i>
                                    SEPARAÇÃO
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