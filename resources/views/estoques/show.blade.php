@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-8">
        <a href="{{ route('estoques.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-2 mb-4">
            <i class="ph ph-arrow-left"></i> Voltar para Gestão de Estoques
        </a>
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $estoque->nome }}</h1>
                <p class="text-gray-500 flex items-center gap-1 mt-1">
                    <i class="ph ph-map-pin"></i> {{ $estoque->localizacao }}
                </p>
            </div>
            <div class="text-right text-sm text-gray-500">
                <span class="block font-bold text-indigo-600 text-xl">{{ $estoque->equipamentos->count() }}</span>
                Itens no total
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipamento</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nº de Série / Patrimônio</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($estoque->equipamentos as $equipamento)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $equipamento->nome }}</div>
                        <div class="text-xs text-gray-500">{{ $equipamento->modelo ?? 'Modelo não informado' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $equipamento->numero_serie ?? $equipamento->patrimonio ?? 'S/N' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                            Disponível
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                        <button class="text-red-600 hover:text-red-900">Remover</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <i class="ph ph-package text-4xl text-gray-300 block mb-2"></i>
                        <span class="text-gray-500">Este estoque está vazio no momento.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection