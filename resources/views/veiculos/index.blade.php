@extends('layouts.app')

@section('subtitle', 'Gerenciamento / Veículos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Frota de Veículos</h1>
    <a href="{{ route('veiculos.create') }}" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-slate-700 transition flex items-center gap-2">
        <i class="ph ph-plus-circle text-xl"></i> Novo Veículo
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($veiculos as $v)
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-slate-100 rounded-lg">
                <i class="ph ph-car text-2xl text-slate-600"></i>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('veiculos.edit', $v->id) }}" class="text-slate-400 hover:text-blue-600"><i class="ph ph-pencil-simple text-xl"></i></a>
                <form action="{{ route('veiculos.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Excluir veículo?')">
                    @csrf @method('DELETE')
                    <button class="text-slate-400 hover:text-red-600"><i class="ph ph-trash text-xl"></i></button>
                </form>
            </div>
        </div>
        <h3 class="text-lg font-bold text-slate-800">{{ $v->modelo }}</h3>
        <p class="text-slate-500 text-sm mb-4">{{ $v->marca ?? 'Marca não informada' }}</p>
        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
            <span class="bg-slate-900 text-white px-3 py-1 rounded-md font-mono text-sm tracking-widest">
                {{ strtoupper($v->placa) }}
            </span>
            <span class="text-xs text-slate-400">ID #{{ $v->id }}</span>
        </div>
    </div>
    @endforeach
</div>
@endsection