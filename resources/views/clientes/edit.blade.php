@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center gap-4 mb-8">
        {{-- Volta para o show se for unidade, ou index se for secretaria --}}
        <a href="{{ $cliente->parent_id ? route('clientes.show', $cliente->parent_id) : route('clientes.index') }}" 
           class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-red-600 transition-all">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">
                Editar {{ $cliente->tipo == 'ministerio' ? 'Secretaria' : 'Unidade' }}
            </h1>
            <p class="text-slate-500 text-sm">Atualize as informações de {{ $cliente->nome }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Preserva o vínculo se for unidade --}}
            @if($cliente->parent_id)
                <input type="hidden" name="parent_id" value="{{ $cliente->parent_id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Razão Social / Nome da Unidade</label>
                    <input type="text" name="nome" value="{{ $cliente->nome }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                </div>

                {{-- CNPJ: Unidades podem ter CNPJ próprio ou usar o da Secretaria --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">CNPJ</label>
                    <input type="text" name="cnpj" value="{{ $cliente->cnpj }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Contrato: Só editável por Secretarias --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Contrato</label>
                    @if($cliente->tipo == 'ministerio')
                        <select name="contrato" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                            @foreach(['Alucom', 'Moreia', 'ZapLok', 'IP'] as $opcao)
                                <option value="{{ $opcao }}" {{ $cliente->contrato == $opcao ? 'selected' : '' }}>{{ $opcao }}</option>
                            @endforeach
                        </select>
                    @else
                        {{-- Se for unidade, exibe apenas como texto e manda o valor original escondido --}}
                        <div class="w-full rounded-xl border-slate-100 bg-slate-100 p-3 text-slate-500 font-bold italic">
                            Contrato vinculado à Secretaria
                        </div>
                        <input type="hidden" name="contrato" value="{{ $cliente->contrato }}">
                    @endif
                </div>

                {{-- Endereço --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Endereço Completo</label>
                    <input type="text" name="endereco" value="{{ $cliente->endereco }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Localização --}}
                <div class="grid grid-cols-3 gap-4 md:col-span-2">
                    <div class="col-span-1">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">UF</label>
                        <input type="text" name="estado" value="{{ $cliente->estado }}" required maxlength="2" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 text-center uppercase font-bold">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Cidade</label>
                        <input type="text" name="cidade" value="{{ $cliente->cidade }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 font-bold">
                    </div>
                </div>
            </div>

            {{-- SLA: Só aparece se for Secretaria. Unidades herdam o SLA da Secretaria Pai --}}
            @if($cliente->tipo == 'ministerio')
            <div class="pt-6 border-t border-slate-100">
                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-4">Configurações de SLA (Horas)</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php 
                        $slas_list = ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72];
                    @endphp
                    
                    @foreach($slas_list as $label => $default)
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">{{ $label }}</label>
                        <input type="number" name="sla[{{ $label }}]" value="{{ $cliente->sla[$label] ?? $default }}" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 outline-none focus:ring-2 focus:ring-red-500 text-sm font-bold">
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tipo de Insumo</label>
                    <select name="sla[Tipo]" class="w-full md:w-1/3 rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none focus:ring-2 focus:ring-red-500 font-bold">
                        <option value="Compativel" {{ ($cliente->sla['Tipo'] ?? '') == 'Compativel' ? 'selected' : '' }}>Compatível</option>
                        <option value="Original" {{ ($cliente->sla['Tipo'] ?? '') == 'Original' ? 'selected' : '' }}>Original</option>
                    </select>
                </div>
            </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('clientes.show', $cliente->id) }}" class="px-6 py-3 font-bold text-slate-500 hover:text-slate-700 transition-all">Cancelar</a>
                <button type="submit" class="px-10 py-3 font-black text-white bg-red-600 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection