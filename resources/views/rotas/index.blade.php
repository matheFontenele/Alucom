@extends('layouts.app')

@section('subtitle', 'Logística / Painel de Rotas')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Fluxo de Entregas</h1>
        <p class="text-slate-500 text-sm">Gerencie o carregamento e saída dos veículos</p>
    </div>
    <a href="{{ route('rotas.create') }}" class="bg-red-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-900/20 flex items-center gap-2">
        <i class="ph ph-plus-circle text-xl"></i> Nova Rota / Carga
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($rotas as $rota)
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
        <div class="p-5 border-b border-slate-50 flex justify-between items-center {{ $rota->status == 'Em Rota' ? 'bg-blue-50/50' : 'bg-slate-50/30' }}">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-600">
                    <i class="ph ph-truck text-2xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-black text-slate-400 uppercase">Status</span>
                    <span class="inline-flex items-center gap-1.5 text-sm font-bold 
                        {{ $rota->status == 'Em Rota' ? 'text-blue-600' : ($rota->status == 'Entregue' ? 'text-emerald-600' : 'text-orange-600') }}">
                        <span class="w-2 h-2 rounded-full animate-pulse {{ $rota->status == 'Em Rota' ? 'bg-blue-600' : ($rota->status == 'Entregue' ? 'bg-emerald-600' : 'bg-orange-600') }}"></span>
                        {{ $rota->status }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <span class="block text-[10px] font-black text-slate-400 uppercase">Saída</span>
                <span class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($rota->data_saida)->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold border-2 border-white shadow-sm">
                    {{ substr($rota->motorista->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-slate-800 font-bold leading-none mb-1">{{ $rota->motorista->name }}</h4>
                    <p class="text-xs text-slate-500 font-medium">Placa: <span class="text-slate-700 font-bold uppercase">{{ $rota->veiculo->placa }}</span> ({{ $rota->veiculo->modelo }})</p>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-start gap-3">
                    <i class="ph ph-map-pin text-red-500 mt-1"></i>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase">Destino Principal</span>
                        <p class="text-sm font-bold text-slate-700">{{ $rota->cidade_destino }} - {{ $rota->estado_destino }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="ph ph-package text-blue-500 mt-1"></i>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase">Carga Selecionada</span>
                        <p class="text-sm font-bold text-slate-700">{{ $rota->requisicoes->count() }} Requisições</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Resumo do Carregamento</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($rota->requisicoes->take(3) as $req)
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 text-[10px] font-bold text-slate-600">
                            #{{ $req->id }} {{ Str::limit($req->cliente->nome, 10) }}
                        </span>
                    @endforeach
                    @if($rota->requisicoes->count() > 3)
                        <span class="text-[10px] font-bold text-slate-400 mt-1">+{{ $rota->requisicoes->count() - 3 }} outros</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('rotas.show', $rota->id) }}" class="text-xs font-bold text-slate-500 hover:text-red-600 transition flex items-center gap-1">
                <i class="ph ph-eye text-lg"></i> Detalhes da Carga
            </a>
            <div class="flex gap-2">
                <button title="Imprimir Manifesto" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-slate-600 hover:shadow-sm transition">
                    <i class="ph ph-printer"></i>
                </button>
                <button title="Finalizar Entrega" class="p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 shadow-sm transition">
                    <i class="ph ph-check-bold"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-truck text-4xl text-slate-300"></i>
        </div>
        <h3 class="text-slate-800 font-bold">Nenhuma rota ativa</h3>
        <p class="text-slate-500 text-sm">Crie uma nova rota para despachar as requisições separadas.</p>
    </div>
    @endforelse
</div>
@endsection