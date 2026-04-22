@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('tecnicos.index') }}" class="w-12 h-12 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 transition-all">
                <i class="ph ph-arrow-left text-slate-400 font-bold"></i>
            </a>
            <div>
                <span class="text-red-600 text-[10px] font-black uppercase tracking-widest">TÉCNICO PARCEIRO</span>
                <h1 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $tecnico->nome }}</h1>
                <p class="text-slate-400 font-bold uppercase text-[10px]">{{ $tecnico->regiao }}</p>
            </div>
        </div>
        <a href="{{ route('tecnicos.edit', $tecnico->id) }}" class="bg-amber-500 text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-amber-100 flex items-center gap-2">
            <i class="ph ph-pencil-simple"></i> Editar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Card de Dados --}}
        <div class="md:col-span-2 bg-white rounded-[40px] p-10 border border-slate-100 shadow-sm relative">
            <span class="absolute top-8 right-8 bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase">Ativo</span>
            
            <h3 class="text-slate-800 font-black uppercase text-xs mb-8 tracking-widest">Ficha Cadastral</h3>

            <div class="grid grid-cols-2 gap-10">
                <div>
                    <label class="block text-[10px] font-black text-slate-300 uppercase mb-1">CNPJ</label>
                    <p class="font-black text-slate-700">{{ $tecnico->cnpj ?? 'Não Informado' }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-300 uppercase mb-1">Preço Base</label>
                    <p class="text-2xl font-black text-slate-800 font-mono">R$ {{ number_format($tecnico->preco_atendimento, 2, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-300 uppercase mb-1">Especialidade</label>
                    <p class="font-black text-slate-700 uppercase">{{ $tecnico->tipo }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-300 uppercase mb-1">Data de Cadastro</label>
                    <p class="font-black text-slate-700">{{ $tecnico->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50">
                <label class="block text-[10px] font-black text-slate-300 uppercase mb-2 tracking-widest text-center">Contato Rápido</label>
                <div class="bg-slate-800 rounded-2xl p-4 flex items-center justify-center gap-4 text-white">
                    <i class="ph ph-whatsapp-logo text-2xl text-green-400"></i>
                    <span class="font-black tracking-wider">{{ $tecnico->contato }}</span>
                </div>
            </div>
        </div>

        {{-- Espaço para Foto ou Logo --}}
        <div class="bg-slate-50 rounded-[40px] border-4 border-white shadow-inner flex flex-col items-center justify-center p-8 text-center">
            <div class="w-32 h-32 rounded-full bg-white shadow-sm flex items-center justify-center mb-4">
                <i class="ph ph-user text-5xl text-slate-200"></i>
            </div>
            <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Sem foto cadastrada</p>
        </div>
    </div>
</div>
@endsection