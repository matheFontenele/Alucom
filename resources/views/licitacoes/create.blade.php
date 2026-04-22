@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Novo Edital / Contrato</h1>
        <p class="text-slate-500">Cadastre os dados principais para controle de faturamento.</p>
    </div>

    <form action="{{ route('licitacoes.store') }}" method="POST" class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Órgão Contratante</label>
                <input type="text" name="uasg_organ" placeholder="Ex: Autarquia Municipal de Trânsito de Caucaia" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500" required>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Número do Contrato/ARP</label>
                <input type="text" name="contract_number" placeholder="Ex: 2021.08.02.01-19" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Pregão Eletrônico</label>
                <input type="text" name="pregao_number" placeholder="Ex: 001/2026" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500" required>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Teto Mensal de Faturamento (R$)</label>
                <input type="number" step="0.01" name="max_monthly_billing" placeholder="7432.50" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500" required>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Vigência (Meses)</label>
                <input type="number" name="validity_months" value="12" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Objeto do Contrato</label>
                <textarea name="object" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500"></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-red-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200">
                Criar Contrato e Adicionar Itens
            </button>
        </div>
    </form>
</div>
@endsection