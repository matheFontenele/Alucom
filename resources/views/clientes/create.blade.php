@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clientes.index') }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-red-600 transition-all">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">Novo Cliente</h1>
            <p class="text-slate-500 text-sm">Preencha os dados para cadastrar um novo cliente no sistema.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 p-6 text-white">
            <h3 class="font-black text-xl">Formulário de Cadastro</h3>
        </div>
        {{-- Em caso de não salvar --}} @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li class="font-bold text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('clientes.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Ministerio/Secretaria --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Tipo de Cadastro</label>
                        <select name="tipo" id="tipo_cliente" class="w-full rounded-xl border-slate-200 bg-white p-3 font-bold outline-none focus:ring-2 focus:ring-red-500">
                            <option value="ministerio">Novo Ministério / Secretaria</option>
                            <option value="unidade" {{ request('parent_id') ? 'selected' : '' }}>Nova Unidade (Filial)</option>
                        </select>
                    </div>

                    <div id="div_parent_id" class="{{ request('parent_id') ? '' : 'hidden' }}">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Vincular ao Ministério</label>
                        <select name="parent_id" class="w-full rounded-xl border-slate-200 bg-white p-3 font-bold outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecione o Ministério Pai...</option>
                            @foreach($ministerios as $m)
                            <option value="{{ $m->id }}" {{ request('parent_id') == $m->id ? 'selected' : '' }}>{{ $m->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <script>
                    // Lógica para mostrar/esconder a seleção de ministério pai
                    document.getElementById('tipo_cliente').addEventListener('change', function() {
                        const divParent = document.getElementById('div_parent_id');
                        if (this.value === 'unidade') {
                            divParent.classList.remove('hidden');
                        } else {
                            divParent.classList.add('hidden');
                        }
                    });
                </script>

                {{-- Nome / Razão Social --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Razão Social / Nome</label>
                    <input type="text" name="nome" required placeholder="Ex: Supermercado Alvorada" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- CNPJ --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">CNPJ</label>
                    <input type="text" name="cnpj" required placeholder="00.000.000/0000-00" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Contrato --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Contrato</label>
                    <select name="contrato" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                        <option value="">Selecione o Contrato...</option>
                        <option value="Alucom">Alucom</option>
                        <option value="Moreia">Moreia</option>
                        <option value="ZapLok">ZapLok</option>
                    </select>
                </div>

                {{-- Endereço --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Endereço Completo</label>
                    <input type="text" name="endereco" required placeholder="Rua, Número, Bairro" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                {{-- Localização --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">UF</label>
                    <input type="text" name="estado" required maxlength="2" placeholder="CE" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 text-center uppercase">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Cidade</label>
                    <input type="text" name="cidade" required placeholder="Fortaleza" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            {{-- Seção de SLA --}}
            <div class="pt-6 border-t border-slate-100">
                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-4">Configurações de SLA (Horas)</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Atendimento</label>
                        <input type="number" name="sla[Atendimento]" value="4" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Insumos</label>
                        <input type="number" name="sla[Insumos]" value="24" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Substituição</label>
                        <input type="number" name="sla[Substituição]" value="48" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Remanejamento</label>
                        <input type="number" name="sla[Remanejamento]" value="72" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tipo de Insumo</label>
                    <select name="sla[Tipo]" class="w-full md:w-1/2 rounded-lg border-slate-200 bg-slate-50 p-2 text-sm outline-none">
                        <option value="Compativel">Compatível</option>
                        <option value="Original">Original</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('clientes.index') }}" class="px-6 py-3 font-bold text-slate-500 hover:text-slate-700 transition-all">Cancelar</a>
                <button type="submit" class="px-10 py-3 font-black text-white bg-red-600 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                    Cadastrar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection