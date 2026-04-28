@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        {{-- Cabeçalho com Status --}}
        <div class="bg-blue-900 p-6 flex justify-between items-center">
            <div>
                <h2 class="text-white text-2xl font-bold flex items-center gap-2">
                    <i class="ph ph-article"></i> Detalhes da Requisição #{{ str_pad($requisicao->id, 4, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-blue-200 text-sm">Ofício: {{ $requisicao->oficio }}</p>
            </div>

            {{-- Badge de Status Colorida --}}
            @php
            $statusStyle = match($requisicao->situacao) {
            'Pendente' => 'bg-amber-400 text-amber-950',
            'Atendida' => 'bg-green-400 text-green-950',
            'Parcialmente' => 'bg-blue-400 text-blue-950',
            'Solicitado Compra' => 'bg-purple-400 text-purple-950',
            'Finalizada' => 'bg-gray-400 text-gray-950',
            default => 'bg-white/20 text-white'
            };
            @endphp
            <div class="{{ $statusStyle }} px-4 py-2 rounded-lg font-black uppercase text-xs shadow-sm">
                {{ $requisicao->situacao }}
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- Grid de Informações Principais --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Solicitante</h4>
                    <p class="text-gray-800 font-semibold">{{ $requisicao->solicitante }}</p>
                    <p class="text-gray-500 text-sm">{{ $requisicao->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Cliente</h4>
                    <p class="text-gray-800 font-semibold">{{ $requisicao->cliente->nome ?? 'Não identificado' }}</p>
                    <p class="text-gray-500 text-sm">{{ $requisicao->cidade }} - {{ $requisicao->estado }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Logística</h4>
                    <p class="text-gray-800 font-semibold">NFE: {{ $requisicao->nfe ?? 'Sem NF' }}</p>
                    <p class="text-gray-500 text-sm">Modalidade: {{ $requisicao->envio }}</p>
                </div>
            </div>

            {{-- Detalhes do Item (CORRIGIDO) --}}
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xs font-black uppercase text-blue-600 mb-1">Item Solicitado</h4>
                        {{-- Usando item_descricao em vez de item->nome --}}
                        <p class="text-blue-900 text-xl font-bold">{{ $requisicao->quantidade }}x {{ $requisicao->item_descricao }}</p>
                        <p class="text-blue-700 text-sm">{{ $requisicao->categoria }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-[10px] font-black uppercase shadow-sm">
                            {{ $requisicao->tipo_solicitacao }}
                        </span>
                    </div>
                </div>

                @if($requisicao->tipo_solicitacao === 'Substituição')
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <h4 class="text-xs font-black uppercase text-blue-600 mb-1">Patrimônio Substituído</h4>
                    <p class="text-blue-900 font-mono font-bold">{{ $requisicao->patrimonio_substituido ?? 'Não informado' }}</p>
                </div>
                @endif

                @if($requisicao->patrimonio_novo)
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <h4 class="text-xs font-black uppercase text-green-600 mb-1">Patrimônio Designado (Novo)</h4>
                    <p class="text-green-900 font-mono font-bold">{{ $requisicao->patrimonio_novo }}</p>
                </div>
                @endif
            </div>

            {{-- Datas e Prazos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                    <i class="ph ph-calendar-blank text-blue-600"></i>
                    <span class="text-sm">Previsão de Envio: <strong>{{ $requisicao->previsao_envio ? \Carbon\Carbon::parse($requisicao->previsao_envio)->format('d/m/Y') : 'Não informada' }}</strong></span>
                </div>
                @if($requisicao->data_separacao)
                <div class="flex items-center gap-2 text-gray-600 bg-green-50 px-4 py-2 rounded-lg border border-green-100">
                    <i class="ph ph-package text-green-600"></i>
                    <span class="text-sm">Separado em: <strong>{{ \Carbon\Carbon::parse($requisicao->data_separacao)->format('d/m/Y') }}</strong></span>
                </div>
                @endif
            </div>

            {{-- Ações Inferiores --}}
            <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                <a href="{{ route('requisicoes.index') }}" class="text-gray-400 font-bold hover:text-blue-900 flex items-center gap-2 transition-colors">
                    <i class="ph ph-arrow-left"></i> Voltar para Lista
                </a>

                <div class="flex gap-3">
                    <a href="{{ route('requisicoes.edit', $requisicao->id) }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        EDITAR
                    </a>

                    {{-- Só mostra o botão de separação se ainda não estiver finalizada --}}
                    @if($requisicao->situacao !== 'Finalizada' && $requisicao->situacao !== 'Atendida')
                    <a href="{{ route('requisicoes.separacao', $requisicao->id) }}" class="bg-blue-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-800 shadow-lg transition flex items-center gap-2">
                        <i class="ph ph-package"></i> IR PARA SEPARAÇÃO
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection