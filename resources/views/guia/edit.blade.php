@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    {{-- Cabeçalho --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('guia-adi.show', $guiaAdi->id) }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-red-600 transition-all">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">Editar Equipamento</h1>
            <p class="text-slate-500 text-sm">Atualizando: {{ $guiaAdi->marca_modelo }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden">
        <form action="{{ route('guia-adi.update', $guiaAdi->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Fabricante --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Fabricante</label>
                    <input type="text" name="fabricante" value="{{ $guiaAdi->fabricante }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Marca/Modelo --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Marca / Modelo</label>
                    <input type="text" name="marca_modelo" value="{{ $guiaAdi->marca_modelo }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Toner --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Modelo do Toner</label>
                    <input type="text" name="toner" value="{{ $guiaAdi->toner }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Foto do Equipamento</label>
                    <input type="file" name="foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-red-50 file:text-red-600 hover:file:bg-red-100">
                </div>
            </div>

            {{-- Especificações Técnicas --}}
            <div class="pt-6 border-t border-slate-100">
                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-4">Especificações Técnicas</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">PPM (Velocidade)</label>
                        <input type="text" name="ppm" value="{{ $guiaAdi->ppm }}" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Rendimento</label>
                        <input type="text" name="rendimento" value="{{ $guiaAdi->rendimento }}" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Voltagem</label>
                        <select name="voltagem" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none focus:ring-2 focus:ring-red-500">
                            <option value="110v" {{ $guiaAdi->voltagem == '110v' ? 'selected' : '' }}>110v</option>
                            <option value="220v" {{ $guiaAdi->voltagem == '220v' ? 'selected' : '' }}>220v</option>
                            <option value="Bivolt" {{ $guiaAdi->voltagem == 'Bivolt' ? 'selected' : '' }}>Bivolt</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Duplex</label>
                        <select name="duplex" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none focus:ring-2 focus:ring-red-500">
                            <option value="Sim" {{ $guiaAdi->duplex == 'Sim' ? 'selected' : '' }}>Sim</option>
                            <option value="Não" {{ $guiaAdi->duplex == 'Não' ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Observações --}}
            <div class="pt-6 border-t border-slate-100">
                <label class="block text-xs font-black text-slate-500 uppercase mb-2">Observações Técnicas</label>
                <textarea name="obs" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">{{ $guiaAdi->obs }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('guia-adi.show', $guiaAdi->id) }}" class="px-6 py-3 font-bold text-slate-500 hover:text-slate-700 transition-all">Cancelar</a>
                <button type="submit" class="px-10 py-3 font-black text-white bg-red-600 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                    Atualizar Equipamento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection