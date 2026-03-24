@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-blue-900 p-4 flex justify-between items-center">
            <h2 class="text-white text-lg font-bold flex items-center gap-2">
                <i class="ph ph-printer"></i>
                Detalhes do Equipamento: {{ $equipamento->nome }}
            </h2>
            <a href="{{ route('equipamentos.index') }}" class="text-white hover:underline text-sm">Voltar</a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Informações Básicas --}}
            <div class="space-y-3">
                <h3 class="font-bold text-gray-700 border-b pb-1">Informações Gerais</h3>
                <p><strong>Tombo:</strong> {{ $equipamento->tombo ?? 'N/A' }}</p>
                <p><strong>Serial:</strong> {{ $equipamento->serial ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $equipamento->status == 'Disponivel' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $equipamento->status }}
                    </span>
                </p>
                <p><strong>Situação:</strong> {{ $equipamento->situacao ?? 'Sem situação' }}</p>
            </div>

            {{-- Localização e Catálogo --}}
            <div class="space-y-3">
                <h3 class="font-bold text-gray-700 border-b pb-1">Localização e Categoria</h3>
                <p><strong>Estoque Atual:</strong> {{ $equipamento->estoque->nome ?? 'Não alocado' }}</p>
                <p><strong>Categoria:</strong> {{ $equipamento->categoria->nome ?? 'N/A' }}</p>
                <p><strong>Modelo (Catálogo):</strong> {{ $equipamento->catalogo->modelo ?? 'N/A' }}</p>
            </div>

            @if($equipamento->observacoes)
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-bold text-gray-700 border-b pb-1">Observações</h3>
                <p class="text-gray-600 mt-2 italic">{{ $equipamento->observacoes }}</p>
            </div>
            @endif
        </div>

        <div class="bg-gray-50 p-4 border-t flex justify-end gap-3">
            <a href="{{ route('equipamentos.edit', $equipamento->id) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600">
                Editar Equipamento
            </a>
        </div>
    </div>
</div>
@endsection