@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Guia ADI</h1>
            <p class="text-slate-500 text-sm">Catálogo técnico e especificações de equipamentos.</p>
        </div>
        <a href="{{ route('guia-adi.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-500/30 transition-all flex items-center gap-2">
            <i class="ph ph-plus-circle text-xl"></i>
            Novo Equipamento
        </a>
    </div>

    {{-- Campo de Busca --}}
    <form action="{{ route('guia-adi.index') }}" method="GET" class="relative w-full max-w-md">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Busque por modelo, fabricante ou toner (ex: TN-3492)..."
            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-sm">
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @forelse($guias as $guia)
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden hover:shadow-2xl transition-all group flex flex-col">

            <div class="h-48 bg-slate-100 flex items-center justify-center border-b border-slate-100 p-4">
                @if($guia->foto)
                <img src="{{ asset('storage/' . $guia->foto) }}" alt="{{ $guia->marca_modelo }}" class="max-h-full object-contain mix-blend-multiply">
                @else
                <i class="ph ph-printer text-6xl text-slate-300 group-hover:scale-110 transition-transform"></i>
                @endif
            </div>

            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ $guia->fabricante }}</span>
                    <h3 class="text-lg font-bold text-slate-800 leading-tight">{{ $guia->marca_modelo }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $guia->familia ?? 'Família não especificada' }}</p>
                </div>

                <div class="space-y-2 mb-6 flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500"><i class="ph ph-drop text-cyan-500 mr-1"></i> Toner:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->toner ?? 'N/D' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500"><i class="ph ph-files text-slate-400 mr-1"></i> Rendimento:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->rendimento ?? 'N/D' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500"><i class="ph ph-gauge text-red-400 mr-1"></i> Velocidade:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->ppm ? $guia->ppm . ' PPM' : 'N/D' }}</span>
                    </div>
                </div>

                <a href="{{ route('guia-adi.show', $guia->id) }}" class="block w-full text-center py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold rounded-xl text-sm border border-slate-200 transition-colors">
                    Ver Especificações Completas
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-printer text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Nenhum equipamento cadastrado</h3>
            <p class="text-slate-500 text-sm mb-6">Comece adicionando a primeira impressora ao seu catálogo ADI.</p>
            <a href="{{ route('guia-adi.create') }}" class="text-red-600 font-bold hover:underline">
                + Adicionar Primeiro Equipamento
            </a>
        </div>
        @endforelse

    </div>
</div>
@endsection