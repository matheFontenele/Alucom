@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    {{-- Cabeçalho com Ações --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('guia-adi.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50 transition-all">
                <i class="ph ph-caret-left font-bold text-slate-600"></i>
            </a>
            <div>
                <span class="text-xs font-black text-red-600 uppercase tracking-widest">{{ $guiaAdi->fabricante }}</span>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none">{{ $guiaAdi->marca_modelo }}</h1>
                <p class="text-slate-400 text-sm mt-1 font-medium">{{ $guiaAdi->familia ?? 'Linha Comercial' }}</p>
            </div>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('guia-adi.edit', $guiaAdi->id) }}" class="flex-1 md:flex-none px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-amber-200 flex items-center justify-center gap-2">
                <i class="ph ph-pencil-simple-line"></i>
                Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Lado Esquerdo: Foto e Insumo --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center justify-center min-h-[300px]">
                @if($guiaAdi->foto)
                    <img src="{{ asset('storage/' . $guiaAdi->foto) }}" alt="{{ $guiaAdi->marca_modelo }}" class="max-w-full h-auto object-contain">
                @else
                    <div class="text-center">
                        <i class="ph ph-printer text-8xl text-slate-100"></i>
                        <p class="text-[10px] font-black text-slate-300 uppercase mt-4 tracking-tighter">Imagem não disponível</p>
                    </div>
                @endif
            </div>

            <div class="bg-slate-900 rounded-[2rem] p-6 text-white relative overflow-hidden">
                <i class="ph ph-drop absolute -right-4 -bottom-4 text-8xl text-white/5 rotate-12"></i>
                <p class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-widest relative z-10">Insumo Recomendado</p>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-900/50">
                        <i class="ph ph-drop-half-bottom text-white text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400">Modelo do Toner</p>
                        <p class="font-black text-xl tracking-tight text-white">{{ $guiaAdi->toner }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lado Direito: Especificações --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest">Ficha Técnica</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-black uppercase">Ativo</span>
                </div>

                <div class="p-8 grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Velocidade (PPM)</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->ppm }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rendimento</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->rendimento }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Duplex</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->duplex ?? 'Não' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resolução Máx.</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->resolucao ?? '600 DPI' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Memória Padrão</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->memoria ?? '128 MB' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Voltagem</p>
                        <p class="font-bold text-slate-700 text-lg">{{ $guiaAdi->voltagem ?? '110v' }}</p>
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Recursos Disponíveis</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $listaFuncoes = is_array($guiaAdi->funcoes) ? $guiaAdi->funcoes : explode(',', $guiaAdi->funcoes);
                        @endphp
                        @foreach($listaFuncoes as $funcao)
                        <span class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase border border-slate-200">
                            <i class="ph ph-check-circle text-red-500 mr-1"></i>
                            {{ trim($funcao) }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Observações --}}
            @if($guiaAdi->obs)
            <div class="bg-amber-50 border-l-4 border-amber-400 rounded-2xl p-6">
                <div class="flex gap-3">
                    <i class="ph ph-warning-circle text-amber-500 text-2xl"></i>
                    <div>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Observação Técnica</p>
                        <p class="text-sm text-amber-900 leading-relaxed font-medium">{{ $guiaAdi->obs }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection