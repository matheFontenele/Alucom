@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Guia de Computadores</h1>
            <p class="text-slate-500 text-sm">Desktops, Notebooks e Servidores.</p>
        </div>
        <a href="{{ route('guia-computadores.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-500/30 transition-all flex items-center gap-2">
            <i class="ph ph-plus-circle text-xl"></i>
            Novo Computador
        </a>
    </div>

    <form action="{{ route('guia-computadores.index') }}" method="GET" class="relative w-full max-w-md mb-8">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Busque por processador, modelo ou geração..." class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-sm">
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($guias as $guia)
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden hover:shadow-2xl transition-all group flex flex-col">
            <div class="h-48 bg-slate-100 flex items-center justify-center p-4">
                @if($guia->foto)
                <img src="{{ asset('storage/' . $guia->foto) }}" class="max-h-full object-contain mix-blend-multiply">
                @else
                <i class="ph ph-desktop text-6xl text-slate-300"></i>
                @endif
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ $guia->fabricante }}</span>
                    <h3 class="text-lg font-bold text-slate-800 leading-tight">{{ $guia->marca_modelo }}</h3>
                </div>
                <div class="space-y-2 mb-6 flex-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">CPU:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->processador }} ({{ $guia->geracao }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">RAM:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->memoria }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Disc:</span>
                        <span class="font-semibold text-slate-700">{{ $guia->armazenamento }}</span>
                    </div>
                </div>
                <a href="{{ route('guia-computadores.show', $guia->id) }}" class="block w-full text-center py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold rounded-xl text-sm border border-slate-200 transition-colors">
                    Ver Especificações
                </a>
            </div>
        </div>
        @empty
        <p class="col-span-full text-center py-10 text-slate-400">Nenhum computador cadastrado.</p>
        @endforelse
    </div>
</div>
@endsection