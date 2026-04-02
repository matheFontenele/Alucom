@extends('layouts.app')

@section('subtitle', 'Logística / Manifesto de Carga')

@section('content')
{{-- ========================================== --}}
{{-- MODO TELA (Invisível na impressão)         --}}
{{-- ========================================== --}}
<div class="max-w-5xl mx-auto print:hidden">
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('rotas.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-2 mb-2 transition text-sm font-bold">
                <i class="ph ph-arrow-left"></i> Voltar para Rotas
            </a>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Detalhes da Rota #{{ $rota->id }}</h1>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold hover:bg-slate-700 transition flex items-center gap-2 shadow-lg shadow-slate-900/10">
                <i class="ph ph-printer text-xl"></i> Imprimir Manifesto
            </button>
            <form action="{{ route('rotas.update', $rota->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="Entregue">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-900/10">
                    <i class="ph ph-check-circle text-xl"></i> Finalizar Rota
                </button>
            </form>
        </div>
    </div>

    {{-- O Seu Card Original da Tela --}}
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
                            <td class="px-6 py-4 text-sm">{{ $req->catalogo->modelo ?? 'Modelo não encontrado' }}</td>
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

{{-- ========================================== --}}
{{-- MODO IMPRESSÃO (Oculto na tela, visível só ao imprimir) --}}
{{-- ========================================== --}}
<div class="hidden print:block w-full text-black bg-white" style="font-family: Arial, sans-serif;">
    <table class="w-full border-collapse border border-black text-[13px] leading-tight">
        <tbody>
            {{-- Cabeçalho ROMANEIO --}}
            <tr>
                <td colspan="3" class="text-center font-bold text-base py-1 border border-black print-bg-yellow" style="background-color: #FFFF00 !important;">ROMANEIO</td>
            </tr>
            
            {{-- Informações do Destino/Cliente --}}
            @php
                // Pega o primeiro cliente como referência para o cabeçalho
                $primeiroCliente = $rota->requisicoes->first()->cliente ?? null;
            @endphp
            <tr>
                <td class="font-bold uppercase border border-black px-1 w-[120px]">CLIENTE:</td>
                <td colspan="2" class="border border-black px-1">{{ $primeiroCliente ? $primeiroCliente->nome : 'DIVERSOS' }}</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">ENDEREÇO:</td>
                <td colspan="2" class="border border-black px-1">{{ $rota->cidade_destino }} - {{ $rota->estado_destino }}</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">CNPJ:</td>
                <td colspan="2" class="border border-black px-1">{{ $primeiroCliente->cnpj ?? '' }}</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">TIPO:</td>
                <td colspan="2" class="border border-black px-1 font-bold" style="color: #CC0000;">
                    <span style="background-color: #FFCCCC !important; padding: 0 4px; border-radius: 2px;" class="print-bg-red-light">ROTA</span>
                </td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">EMPRESA ENTRI:</td>
                <td colspan="2" class="border border-black px-1"></td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">EMPRESA:</td>
                <td colspan="2" class="border border-black px-1 font-bold text-white print-bg-red-dark" style="background-color: #990000 !important;">
                    Alucom
                </td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">SAIDA :</td>
                <td colspan="2" class="border border-black px-1 text-center font-bold">ALUCOM FORTALEZA</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">MOTORISTA:</td>
                <td colspan="2" class="border border-black px-1">{{ $rota->motorista->name }}</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1">VEICULO:</td>
                <td colspan="2" class="border border-black px-1">{{ $rota->veiculo->modelo }} - {{ strtoupper($rota->veiculo->placa) }}</td>
            </tr>

            {{-- Sub-título Equipamentos --}}
            <tr>
                <td colspan="3" class="text-center font-bold py-1 border border-black print-bg-yellow" style="background-color: #FFFF00 !important;">EQUIPAMENTOS</td>
            </tr>
            <tr>
                <td class="font-bold uppercase border border-black px-1 text-center w-[80px]">QUANT.:</td>
                <td class="font-bold uppercase border border-black px-1 w-[100px]">PESO:</td>
                <td class="font-bold uppercase border border-black px-1">TIPO:</td>
            </tr>

            {{-- Loop de Requisições --}}
            @foreach($rota->requisicoes as $req)
            <tr>
                <td class="text-center border border-black px-1">1</td>
                <td class="border border-black px-1"></td>
                <td class="border border-black px-1 uppercase">
                    {{ $req->catalogo->modelo ?? 'EQUIPAMENTO NÃO ESPECIFICADO' }} 
                    {{ $req->patrimonio_novo ? '- PAT: ' . $req->patrimonio_novo : '' }}
                </td>
            </tr>
            @endforeach

            {{-- Linhas em branco extra para preencher papel e ficar igual a planilha --}}
            @for($i = 0; $i < 10; $i++)
            <tr>
                <td class="border border-black px-1 py-[9px]"></td>
                <td class="border border-black px-1"></td>
                <td class="border border-black px-1"></td>
            </tr>
            @endfor

            {{-- Rodapé --}}
            <tr>
                <td colspan="3" class="border border-black px-1 text-xs">
                    No caso de transportadora informar a quantidade de volume
                </td>
            </tr>
            <tr>
                <td colspan="2" class="border border-black px-1">Volume =</td>
                <td class="border border-black px-1 font-bold">{{ $rota->requisicoes->count() }}</td>
            </tr>
            <tr>
                <td colspan="2" class="border border-black px-1">Peso =</td>
                <td class="border border-black px-1"></td>
            </tr>
        </tbody>
    </table>
</div>

<style>
    @media print {
        @page {
            margin: 1cm; /* Margem padrão de impressão */
        }
        body {
            background: white !important;
            /* Instrução super importante para o navegador imprimir os fundos coloridos */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Esconde a interface original, sidebar do admin etc */
        aside, header, nav, .print\:hidden {
            display: none !important;
        }

        /* Removemos paddings do main que podem empurrar a impressão */
        main {
            padding: 0 !important;
            margin: 0 !important;
        }

        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
    }
</style>
@endsection