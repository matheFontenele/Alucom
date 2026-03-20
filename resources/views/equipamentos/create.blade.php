@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl">
                    Nova Entrada - {{ request('tipo') == 'insumo' ? 'Insumo' : 'Equipamento' }}
                </h3>
                <p class="text-blue-100 text-sm">Registrando item diretamente no estoque.</p>
            </div>
            <i class="ph ph-package text-3xl"></i>
        </div>

        <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            {{-- Passa o estoque_id de forma oculta para o banco --}}
            <input type="hidden" name="estoque_id" value="{{ request('estoque_id') }}">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                {{-- Seleção do Modelo Baseado no Catálogo Central --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Modelo do Catálogo</label>
                    <select name="catalogo_id" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione o modelo...</option>
                        {{-- Isso virá do seu novo Controller de Catálogo --}}
                        {{-- @foreach($modelosCatalogo as $modelo)
                            <option value="{{ $modelo->id }}">{{ $modelo->nome }} ({{ $modelo->categoria->nome }})</option>
                        @endforeach --}}
                    </select>
                </div>

                {{-- O Tombo só aparece se for Equipamento --}}
                @if(request('tipo') !== 'insumo')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Tombo (Max 5)</label>
                    <input type="text" name="tombo" maxlength="5" placeholder="00000" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Número de Série</label>
                    <input type="text" name="serial" placeholder="S/N" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                {{-- Se for insumo, entra quantidade --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Quantidade a adicionar</label>
                    <input type="number" name="quantidade" value="1" min="1" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                @endif

                {{-- Localização travada no Estoque que originou o clique --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Localização (Travada)</label>
                    <div class="w-full rounded-xl border border-blue-200 bg-blue-50 p-3 font-bold text-blue-900 text-sm flex items-center gap-2 cursor-not-allowed">
                        <i class="ph ph-lock-key text-blue-500"></i>
                        {{-- Aqui você pode buscar o nome do estoque no Controller ou passar via query, 
                             mas visualmente fica claro que vai para o estoque atual --}}
                        Estoque Selecionado
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all">
                    Registrar Entrada
                </button>
            </div>
        </form>
    </div>
</div>
@endsection