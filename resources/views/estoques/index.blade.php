@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestão de Estoques</h1>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <a href="{{ route('estoques.create') }}" class="bg-indigo-600 ...">
                + Novo Local de Estoque
            </a>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($estoques as $estoque)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-indigo-900">{{ $estoque->nome }}</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        <i class="ph ph-map-pin"></i> {{ $estoque->localizacao }}
                    </p>
                </div>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full">
                    ID #{{ $estoque->id }}
                </span>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="text-gray-600 font-medium">Equipamentos alocados</span>
                    <span class="text-indigo-600 font-bold">{{ $estoque->equipamentos_count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ min($estoque->equipamentos_count * 5, 100) }}%"></div>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('estoques.show', $estoque->id) }}"
                    class="flex-1 text-center bg-gray-50 text-gray-700 px-3 py-2 rounded-md text-sm font-semibold hover:bg-gray-100 border border-gray-200">
                    Ver Inventário
                </a>
                <button class="p-2 text-gray-400 hover:text-red-600">
                    <i class="ph ph-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection