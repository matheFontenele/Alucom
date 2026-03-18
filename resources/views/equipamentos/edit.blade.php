@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Editar Equipamento: {{ $equipamento->tombo }}</h1>
            <a href="{{ route('equipamentos.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="ph ph-x text-2xl"></i>
            </a>
        </div>

        <form action="{{ route('equipamentos.update', $equipamento->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Equipamento</label>
                    <input type="text" name="nome" value="{{ old('nome', $equipamento->nome) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                </div>

                {{-- Tombo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número do Tombo</label>
                    <input type="text" name="tombo" value="{{ old('tombo', $equipamento->tombo) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                </div>

                {{-- Serial --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Serial</label>
                    <input type="text" name="serial" value="{{ old('serial', $equipamento->serial) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                </div>

                {{-- Categoria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ $equipamento->categoria_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Informação de Status (Bloqueado para edição) --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 md:col-span-2 flex justify-between items-center">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Status Atual</span>
                        <span class="text-sm font-medium text-gray-700">{{ $equipamento->status }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] text-amber-600 bg-amber-50 px-2 py-1 rounded-full border border-amber-100">
                            <i class="ph ph-info mr-1"></i> Alterável apenas via Movimentação
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Atualizar Cadastro
                </button>
            </div>
        </form>
    </div>
</div>
@endsection