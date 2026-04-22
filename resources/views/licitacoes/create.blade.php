@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Novo Edital / Contrato</h1>
        <p class="text-slate-500">Cadastre os dados principais para controle de faturamento.</p>
    </div>

    {{-- BLOCO DE ERROS: Adicione isso para ver o que está travando o envio --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl">
        <p class="font-bold">Atenção:</p>
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('licitacoes.store') }}" method="POST" class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- ... (seus campos de Órgão, Contrato e Pregão) ... --}}

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Teto Mensal (R$)</label>
                <input type="number" step="0.01" name="max_monthly_billing" value="{{ old('max_monthly_billing') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200" required>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Vigência (Meses)</label>
                <input type="number" name="validity_months" value="{{ old('validity_months', 12) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200" required>
            </div>

            {{-- CAMPO FALTANTE QUE CAUSA O ERRO --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Prazo de Entrega (Dias)</label>
                <input type="number" name="delivery_deadline" value="{{ old('delivery_deadline', 30) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2">Objeto do Contrato</label>
                <textarea name="object" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200">{{ old('object') }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-red-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-red-700 transition shadow-lg">
                Criar Contrato e Adicionar Itens
            </button>
        </div>
    </form>
</div>
@endsection