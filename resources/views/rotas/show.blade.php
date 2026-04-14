@extends('layouts.app')

@section('subtitle', 'Logística / Manifesto de Carga')

@section('content')
<div class="max-w-5xl mx-auto print:hidden">
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('rotas.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-2 mb-2 transition text-sm font-bold">
                <i class="ph ph-arrow-left"></i> Voltar para Rotas
            </a>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Detalhes da Rota #{{ $rota->id }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('rotas.imprimir', $rota->id) }}" target="_blank"
                class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold hover:bg-slate-700 transition flex items-center gap-2 shadow-lg">
                <i class="ph ph-printer text-xl"></i> Imprimir Romaneio
            </a>
            <form action="{{ route('rotas.update', $rota->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="Entregue">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-900/10">
                    <i class="ph ph-check-circle text-xl"></i> Finalizar Rota
                </button>
            </form>
        </div>
    </div>

    {{-- Card Original da Tela --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8 border-b-2 border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg">
                    <i class="ph ph-truck"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter leading-none">Manifesto de Transporte</h2>
                    <p class="text-slate-500 font-bold text-sm mt-1 uppercase tracking-widest">Guia ADI - Logística Integrada</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase">Documento Nº</p>
                <p class="text-xl font-mono font-black text-slate-800 tracking-tighter">RT-{{ str_pad($rota->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 border-b border-slate-100">
            <div class="p-6">
                <span class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Responsável</span>
                <p class="font-bold text-slate-800">{{ $rota->motorista->name }}</p>
                <p class="text-xs text-slate-500 font-medium">Motorista Autorizado</p>
            </div>
            <div class="p-6">
                <span class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Veículo</span>
                <p class="font-bold text-slate-800 uppercase">{{ $rota->veiculo->placa }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ $rota->veiculo->modelo }}</p>
            </div>
            <div class="p-6">
                <span class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Previsão</span>
                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($rota->previsao_chegada)->format('d/m/Y') }}</p>
                <p class="text-xs text-slate-500 font-medium">Chegada ao Destino</p>
            </div>
        </div>

        <div class="p-8">
            <h3 class="text-sm font-black text-slate-800 uppercase mb-6 flex items-center gap-2">
                <i class="ph ph-package text-red-500 text-lg"></i> Itens do Carregamento ({{ $rota->requisicoes->count() }})
            </h3>

            <div class="overflow-hidden border border-slate-100 rounded-2xl">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">REQ</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Destinatário / Cliente</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Equipamento / Modelo</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Patrimônio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($rota->requisicoes as $req)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-red-600 text-sm">#{{ $req->id }}</td>
                            <td class="px-6 py-4">
                                <span class="block font-bold">{{ $req->cliente->nome ?? 'Cliente não informado' }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $rota->cidade_destino }} - {{ $rota->estado_destino }}</span>
                            </td>
                            {{-- Ajustado de modelo para nome --}}
                            <td class="px-6 py-4 text-sm">{{ $req->catalogo->nome ?? 'Modelo não encontrado' }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-800 px-2 py-1 rounded font-mono font-bold uppercase text-xs">
                                    {{ $req->patrimonio_novo ?? 'N/D' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($rota->observacoes)
        <div class="px-8 pb-8">
            <div class="bg-orange-50 p-4 rounded-xl border border-orange-100">
                <p class="text-[10px] font-black text-orange-600 uppercase mb-1">Observações da Entrega</p>
                <p class="text-sm text-orange-800 font-medium italic">"{{ $rota->observacoes }}"</p>
            </div>
        </div>
        @endif
    </div>
</div>


<style>
    @media print {
        @page {
            margin: 1cm;
        }

        body {
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        aside,
        header,
        nav,
        .print\:hidden {
            display: none !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection