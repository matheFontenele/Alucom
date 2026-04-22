@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <a href="{{ route('guia-adi.index') }}" class="text-slate-500 hover:text-red-600 font-bold flex items-center gap-2">
            <i class="ph ph-arrow-left"></i> Voltar para a listagem
        </a>
        <h1 class="text-3xl font-black text-slate-800 mt-4">Cadastrar Equipamento</h1>
    </div>

    <form action="{{ route('guia-adi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Fabricante --}}
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Fabricante</label>
                    <input type="text" name="fabricante" placeholder="Ex: Kyocera, Epson" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none" required>
                </div>

                {{-- Modelo --}}
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Marca e Modelo</label>
                    <input type="text" name="marca_modelo" placeholder="Ex: ECOSYS M3655idn" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none" required>
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Foto do Equipamento</label>
                    <input type="file" name="foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>

                {{-- Toner e Rendimento --}}
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Modelo do Toner</label>
                    <input type="text" name="toner" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Rendimento</label>
                    <input type="text" name="rendimento" placeholder="Ex: 25.000 páginas" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                {{-- Funções (Checkbox) --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Funções</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Impressão', 'Cópia', 'Scan', 'Fax'] as $func)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="funcoes[]" value="{{ $func }}" class="w-5 h-5 accent-red-600">
                            <span class="text-sm font-medium text-slate-700">{{ $func }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Observações --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2">Observações Técnicas</label>
                    <textarea name="obs" rows="3" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none"></textarea>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-red-500/30 transition-all uppercase tracking-widest">
                Salvar Equipamento no Guia
            </button>
        </div>
    </form>
</div>
@endsection