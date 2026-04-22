@extends('layouts.app')

@section('subtitle', 'Logística / Nova Rota')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
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
            
            {{-- Coluna de Configurações --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest flex items-center gap-2">
                        <i class="ph ph-steering-wheel text-lg text-red-500"></i> Transporte
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Motorista</label>
                            <select name="user_id" required class="w-full rounded-xl border-slate-200 p-3 text-sm focus:ring-red-500">
                                <option value="">Selecione o Motorista...</option>
                                @foreach($motoristas as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Veículo (Placa)</label>
                            <select name="veiculo_id" required class="w-full rounded-xl border-slate-200 p-3 text-sm focus:ring-red-500">
                                <option value="">Selecione o Veículo...</option>
                                @foreach($veiculos as $v)
                                    <option value="{{ $v->id }}">{{ $v->modelo }} ({{ strtoupper($v->placa) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Saída de</label>
                            <select name="estoque_origem_id" required class="w-full rounded-xl border-slate-200 p-3 text-sm focus:ring-red-500">
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
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Data de Saída</label>
                            <input type="date" name="data_saida" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 p-3 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 ml-1">Previsão Chegada</label>
                            <input type="date" name="previsao_chegada" required class="w-full rounded-xl border-slate-200 p-3 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna de Carga e Destino --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest flex items-center gap-2">
                        <i class="ph ph-map-trifold text-lg text-emerald-500"></i> Destino Principal
                    </h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-3">
                            <input type="text" name="cidade_destino" placeholder="Cidade de Destino Final" required class="w-full rounded-xl border-slate-200 p-3 text-sm">
                        </div>
                        <div class="col-span-1">
                            <input type="text" name="estado_destino" placeholder="UF" maxlength="2" required class="w-full rounded-xl border-slate-200 p-3 text-sm uppercase text-center">
                        </div>
                    </div>
                </div>

                {{-- LISTAGEM DE REQUISIÇÕES --}}
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="ph ph-package text-lg text-indigo-500"></i> Carregamento (Requisições)
                        </h3>
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Pendentes: {{ count($requisicoesPendentes) }}</span>
                    </div>

                    <div class="grid grid-cols-1 gap-3 max-h-[400px] overflow-y-auto pr-2">
                        @forelse($requisicoesPendentes as $req)
                            <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 hover:border-red-200 hover:bg-red-50/30 cursor-pointer transition-all group">
                                <input type="checkbox" name="requisicoes[]" value="{{ $req->id }}" class="w-5 h-5 rounded-lg border-slate-300 text-red-600 focus:ring-red-500 transition">
                                
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-start">
                                        <span class="text-sm font-bold text-slate-700">Ofício: {{ $req->oficio ?? 'Sem Nº' }}</span>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase">{{ $req->tipo_solicitacao ?? 'Novo' }}</span>
                                    </div>
                                    <div class="flex gap-4 mt-1">
                                        <span class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="ph ph-building"></i> {{ $req->cliente->nome ?? 'S/ Cliente' }}
                                        </span>
                                        <span class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="ph ph-map-pin"></i> {{ $req->cidade }}/{{ $req->estado }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-10">
                                <i class="ph ph-archive text-4xl text-slate-200 mb-2"></i>
                                <p class="text-slate-400 text-sm font-medium">Nenhuma requisição aguardando transporte.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-4 tracking-widest flex items-center gap-2">
                        <i class="ph ph-note text-lg text-amber-500"></i> Observações da Viagem
                    </h3>
                    <textarea name="observacoes" rows="3" placeholder="Instruções para o motorista ou notas da rota..." class="w-full rounded-2xl border-slate-200 p-4 text-sm focus:ring-red-500"></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection