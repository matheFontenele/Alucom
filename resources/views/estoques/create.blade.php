@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-6">
        <a href="{{ route('estoques.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-2 transition-colors">
            <i class="ph ph-arrow-left font-bold"></i>
            <span class="font-bold text-sm">Voltar para listagem</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        {{-- Cabeçalho --}}
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl">Novo Local de Estoque</h3>
                <p class="text-slate-400 text-sm">Cadastre um novo ponto de armazenamento para equipamentos.</p>
            </div>
            <i class="ph ph-package text-3xl text-blue-400"></i>
        </div>

        {{-- Exibição de Erros --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl m-6">
            <ul class="list-disc pl-5 text-sm font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('estoques.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nome do Estoque --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Nome do Estoque</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Ex: Estoque Central - Sede" 
                           class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                {{-- Localização --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Localização / Cidade</label>
                    <input type="text" name="localizacao" value="{{ old('localizacao') }}" placeholder="Ex: João Pessoa - PB" 
                           class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('estoques.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Salvar Estoque
                </button>
            </div>
        </form>
    </div>
</div>
@endsection