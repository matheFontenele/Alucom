@extends('layouts.app')

@section('subtitle', 'Novo Usuário')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('usuarios.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-2 mb-2 transition">
            <i class="ph ph-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Cadastrar Colaborador</h1>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Nome Completo</label>
                    <input type="text" name="name" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3" placeholder="Ex: João da Silva">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">E-mail (Login)</label>
                    <input type="email" name="email" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3" placeholder="joao@alucom.com">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Função / Cargo</label>
                    <select name="funcao" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3">
                        <option value="">Selecione...</option>
                        @foreach(['Estoque', 'Técnico', 'Motorista', 'Administrativo', 'Logística', 'Financeiro', 'Gerência', Direção] as $funcao)
                            <option value="{{ $funcao }}">{{ $funcao }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Senha</label>
                    <input type="password" name="password" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3" placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 p-3" placeholder="••••••••">
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 flex gap-4">
                <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-4 rounded-2xl hover:bg-red-700 transition shadow-lg shadow-red-900/20">
                    Finalizar Cadastro
                </button>
                <a href="{{ route('usuarios.index') }}" class="flex-1 bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl text-center hover:bg-slate-200 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection