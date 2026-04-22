@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Novo Edital / Contrato</h1>
        <p class="text-slate-500">Cadastre os dados principais para controle de faturamento.</p>
    </div>

    {{-- BLOCO DE ALERTAS DE ERRO --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm">
        <div class="flex items-center mb-2">
            <i class="ph ph-warning-circle text-xl mr-2"></i>
            <span class="font-bold">Ops! Verifique os dados abaixo:</span>
        </div>
        <ul class="list-disc ml-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('licitacoes.store') }}" method="POST" class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Órgão Contratante --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Órgão Contratante</label>
                <input type="text" name="uasg_organ" value="{{ old('uasg_organ') }}" placeholder="Ex: Autarquia Municipal de Trânsito de Caucaia" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
            </div>

            {{-- Número do Contrato --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Número do Contrato/ARP</label>
                <input type="text" name="contract_number" value="{{ old('contract_number') }}" placeholder="Ex: 2021.08.02.01-19" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
            </div>

            {{-- Pregão --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Pregão Eletrônico</label>
                <input type="text" name="pregao_number" value="{{ old('pregao_number') }}" placeholder="Ex: 001/2026" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
            </div>

            {{-- Teto Financeiro --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Teto Mensal de Faturamento (R$)</label>
                <input type="number" step="0.01" name="max_monthly_billing" value="{{ old('max_monthly_billing') }}" placeholder="7432.50" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
            </div>

            {{-- Vigência --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Vigência (Meses)</label>
                <input type="number" name="validity_months" value="{{ old('validity_months', 12) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
            </div>

            {{-- Prazo de Entrega (ESTAVA FALTANDO) --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Prazo de Entrega (Dias)</label>
                <input type="number" name="delivery_deadline" value="{{ old('delivery_deadline', 30) }}" placeholder="30" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
            </div>

            {{-- Datas Opcionais --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Data de Início (Opcional)</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
            </div>

            {{-- Objeto --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Objeto do Contrato</label>
                <textarea name="object" rows="3" placeholder="Descreva brevemente o objeto da licitação..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>{{ old('object') }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-red-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200 active:transform active:scale-95">
                Criar Contrato e Adicionar Itens
            </button>
        </div>
    </form>
</div>
@endsection