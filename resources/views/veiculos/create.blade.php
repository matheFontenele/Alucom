@extends('layouts.app')

@section('subtitle', 'Novo Veículo')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <form action="{{ route('veiculos.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Modelo do Veículo</label>
                <input type="text" name="modelo" placeholder="Ex: Fiat Fiorino" class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Placa</label>
                    <input type="text" name="placa" placeholder="ABC-1234" class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Marca</label>
                    <input type="text" name="marca" placeholder="Ex: Fiat" class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500">
                </div>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition">Salvar Veículo</button>
            <a href="{{ route('veiculos.index') }}" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl text-center hover:bg-slate-200 transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection