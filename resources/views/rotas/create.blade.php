@extends('layouts.app')

@section('subtitle', 'Logística / Nova Rota')

@section('content')
<div class="max-w-5xl mx-auto">
    <form action="{{ route('rotas.store') }}" method="POST">
        @csrf
        
        <div class="flex justify-between items-end mb-8">
            <div>
                <a href="{{ route('rotas.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-2 mb-2 transition text-sm font-bold">
                    <i class="ph ph-arrow-left"></i> Voltar para o fluxo
                </a>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Montar Nova Rota</h1>
            </div>
            <button type="submit" class="bg-red-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-900/20 flex items-center gap-2">
                <i class="ph ph-truck text-xl"></i> Despachar Rota
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest flex items-center gap-2">
                        <i class="ph ph-steering-wheel text-lg text-red-500"></i> Transporte
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Motorista</label>
                            <select name="user_id" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                                <option value="">Selecione o Motorista...</option>
                                @foreach($motoristas as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Veículo (Placa)</label>
                            <select name="veiculo_id" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                                <option value="">Selecione o Veículo...</option>
                                @foreach($veiculos as $v)
                                    <option value="{{ $v->id }}">{{ $v->modelo }} ({{ strtoupper($v->placa) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Saída de</label>
                            <select name="estoque_origem_id" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                                @foreach($estoques as $e)
                                    <option value="{{ $e->id }}">{{ $e->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest flex items-center gap-2">
                        <i class="ph ph-calendar text-lg text-blue-500"></i> Cronograma
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Data de Saída</label>
                            <input type="date" name="data_saida" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Previsão Chegada</label>
                            <input type="date" name="previsao_chegada" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest flex items-center gap-2">
                        <i class="ph ph-map-trifold text-lg text-emerald-500"></i> Destino da Rota
                    </h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-3">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Cidade Destino</label>
                            <input type="text" name="cidade_destino" placeholder="Ex: Maranguape" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">UF</label>
                            <input type="text" name="estado_destino" placeholder="CE" maxlength="2" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3 text-sm uppercase">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-