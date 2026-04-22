@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <a href="{{ route('tecnicos.index') }}" class="text-slate-400 hover:text-slate-600 font-black text-xs uppercase flex items-center gap-2 mb-4">
            <i class="ph ph-arrow-left"></i> Voltar para lista
        </a>
        <h1 class="text-3xl font-black text-slate-800 tracking-tighter">Novo Técnico</h1>
    </div>

    <form action="{{ route('tecnicos.store') }}" method="POST" class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-200">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nome Completo / Razão Social</label>
                <input type="text" name="nome" required class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">CNPJ (Opcional)</label>
                <input type="text" name="cnpj" id="cnpj" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Região de Atuação</label>
                <input type="text" name="regiao" placeholder="Ex: Belém - PA" required class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Especialidade</label>
                <select name="tipo" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none">
                    <option value="Impressoras">Impressoras</option>
                    <option value="Informatica">Informática</option>
                    <option value="Ambos">Ambos</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Preço do Atendimento (R$)</label>
                <input type="number" step="0.01" name="preco_atendimento" required class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Contato (WhatsApp/E-mail)</label>
                <input type="text" name="contato" required class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold outline-none">
            </div>
        </div>

        <button type="submit" class="mt-8 w-full bg-slate-800 text-white p-4 rounded-2xl font-black hover:bg-slate-700 transition-all uppercase tracking-widest">
            Salvar Técnico
        </button>
    </form>
</div>
@endsection