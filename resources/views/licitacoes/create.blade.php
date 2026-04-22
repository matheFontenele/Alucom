@extends('layouts.app')

@section('title', 'Novo Edital')
@section('subtitle', 'Cadastro de Licitação')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('licitacoes.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-slate-400 hover:text-red-600 transition">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cadastrar Novo Edital</h1>
            <p class="text-slate-500 text-sm">Preencha as informações básicas para iniciar o edital.</p>
        </div>
    </div>

    <form action="{{ route('licitacoes.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Seção: Informações Principais --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-file-text text-red-500"></i>
                Dados do Órgão e Pregão
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Órgão / UASG --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Órgão Público / UASG</label>
                    <input type="text" name="uasg_organ" placeholder="Ex: Conselho Regional de Psicologia - CRP/PR"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" required>
                </div>

                {{-- Número do Pregão --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nº do Pregão</label>
                    <input type="text" name="pregao_number" placeholder="Ex: 001/2026"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 transition" required>
                </div>

                {{-- Prazo de Entrega --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Prazo de Entrega (Dias)</label>
                    <input type="number" name="delivery_deadline" placeholder="Ex: 30"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 transition" required>
                </div>

                {{-- Vigência do Contrato --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Vigência Inicial (Meses)</label>
                    <input type="number" name="validity_months" value="12"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 transition" required>
                </div>

                {{-- Possibilidade de Extensão --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Limite de Extensão (Anos)</label>
                    <input type="number" name="extension_years" value="5"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 transition">
                </div>

                {{-- Objeto --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Objeto da Licitação</label>
                    <textarea name="object" rows="3" placeholder="Descrição sucinta do que está sendo licitado..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 transition" required></textarea>
                </div>
            </div>
        </div>

        {{-- Seção: Configurações Técnicas Rápidas --}}
        <div class="bg-slate-900 rounded-3xl p-8 shadow-xl">
            <h3 class="text-white font-bold mb-6 flex items-center gap-2">
                <i class="ph ph-sliders text-red-500"></i>
                Configurações de Aceite e Exigências
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <label class="flex items-center gap-3 p-4 bg-slate-800 rounded-2xl cursor-pointer hover:bg-slate-700 transition border border-slate-700">
                    <input type="checkbox" name="accepts_used" class="w-5 h-5 rounded border-slate-600 text-red-600 focus:ring-red-500 bg-slate-700">
                    <span class="text-white font-medium">Aceita Equipamentos Seminovos?</span>
                </label>

                <label class="flex items-center gap-3 p-4 bg-slate-800 rounded-2xl cursor-pointer hover:bg-slate-700 transition border border-slate-700">
                    <input type="checkbox" name="requires_office" class="w-5 h-5 rounded border-slate-600 text-red-600 focus:ring-red-500 bg-slate-700">
                    <span class="text-white font-medium">Exige Pacote Office Instalado?</span>
                </label>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="flex justify-end items-center gap-4">
            <a href="{{ route('licitacoes.index') }}" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-700 transition">
                Cancelar
            </a>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-red-900/40 transition-all flex items-center gap-2">
                <i class="ph ph-check-circle text-xl"></i>
                Salvar e Adicionar Itens
            </button>
        </div>
    </form>
</div>
@endsection