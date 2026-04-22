@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        {{-- Cabeçalho com Status --}}
        <div class="bg-blue-900 p-6 flex justify-between items-center">
            <div>
                <h2 class="text-white text-2xl font-bold flex items-center gap-2">
                    <i class="ph ph-article"></i> Detalhes da Requisição #{{ $requisicao->id }}
                </h2>
                <p class="text-blue-200 text-sm">Ofício: {{ $requisicao->oficio }}</p>
            </div>
            <div class="bg-white/20 px-4 py-2 rounded-lg text-white font-bold">
                {{ $requisicao->envio }}
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- Grid de Informações Principais --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Solicitante</h4>
                    <p class="text-gray-800 font-semibold">{{ $requisicao->solicitante }}</p>
                    <p class="text-gray-500 text-sm">{{ date('d/m/Y H:i', strtotime($requisicao->created_at)) }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Cliente</h4>
                    <p class="text-gray-800 font-semibold">{{ $requisicao->cliente->nome }}</p>
                    <p class="text-gray-500 text-sm">{{ $requisicao->cidade }} - {{ $requisicao->estado }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Logística</h4>
                    <p class="text-gray-800 font-semibold">NFE: {{ $requisicao->nfe }}</p>
                    <p class="text-gray-500 text-sm">Etiqueta: {{ $requisicao->etiqueta }}</p>
                </div>
            </div>

            {{-- Detalhes do Item --}}
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xs font-black uppercase text-blue-600 mb-1">Item Solicitado</h4>
                        <p class="text-blue-900 text-xl font-bold">{{ $requisicao->quantidade }}x {{ $requisicao->item->nome }}</p>
                        <p class="text-blue-700 text-sm">{{ $requisicao->item->fabricante }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-bold uppercase">
                            {{ $requisicao->tipo_solicitacao }}
                        </span>
                    </div>
                </div>

                @if($requisicao->tipo_solicitacao === 'Substituição')
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <h4 class="text-xs font-black uppercase text-blue-600 mb-1">Patrimônio Substituído</h4>
                    <p class="text-blue-900 font-mono font-bold">{{ $requisicao->patrimonio_substituido }}</p>
                </div>
                @endif
            </div>

            {{-- Datas e Prazos --}}
            <div class="flex flex-wrap gap-4 border-t border-gray-100 pt-6">
                <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                    <i class="ph ph-calendar-check"></i>
                    <span class="text-sm">Previsão: <strong>{{ $requisicao->previsao_envio ? date('d/m/Y', strtotime($requisicao->previsao_envio)) : 'Não informada' }}</strong></span>
                </div>
            </div>

            {{-- Ações Inferiores --}}
            <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                <a href="{{ route('requisicoes.index') }}" class="text-gray-400 font-bold hover:text-gray-600">
                    <i class="ph ph-arrow-left"></i> Voltar para Lista
                </a>
                
                <div class="flex gap-3">
                    <a href="{{ route('requisicoes.separacao', $requisicao->id) }}" class="bg-blue-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-800 shadow-lg transition">
                        IR PARA SEPARAÇÃO
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection