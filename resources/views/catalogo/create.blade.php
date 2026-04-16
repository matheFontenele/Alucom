@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    {{-- Header de Navegação --}}
    <div class="mb-8">
        <a href="{{ route('catalogos.index') }}" class="text-slate-400 hover:text-blue-600 font-bold text-sm flex items-center gap-2 transition-colors mb-4">
            <i class="ph ph-caret-left"></i> Voltar ao Catálogo
        </a>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Novo Ativo</h1>
        <p class="text-slate-500 font-medium">Cadastre as especificações técnicas do novo modelo.</p>
    </div>

    {{-- Card do Formulário --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-1">Ficha de Cadastro</p>
                <h3 class="font-black text-2xl uppercase tracking-tighter">Especificações Técnicas</h3>
            </div>
            <i class="ph ph-cpu absolute -right-6 -bottom-6 text-9xl text-white/5 rotate-12"></i>
        </div>

        <form action="{{ route('catalogos.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            
            {{-- Informações Básicas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Nome do Modelo</label>
                    <input type="text" name="nome" required placeholder="Ex: Ecosys M3655idn ou ThinkCentre M70q" 
                        class="w-full bg-slate-50 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none p-4 text-sm font-bold transition-all">
                </div>
                
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Fabricante</label>
                    <input type="text" name="fabricante" required placeholder="Ex: Kyocera, Dell, HP..." 
                        class="w-full bg-slate-50 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none p-4 text-sm font-bold transition-all">
                </div>

                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Categoria</label>
                    <select name="categoria_id" id="select-categoria" required 
                        class="w-full bg-slate-50 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none p-4 text-sm font-bold transition-all appearance-none">
                        <option value="">Selecione uma categoria...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" data-nome="{{ $cat->nome }}">{{ $cat->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Área Dinâmica --}}
            <div id="dynamic-fields" class="space-y-6">
                <div id="placeholder-text" class="py-10 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                    <p class="text-slate-400 text-sm font-medium">Selecione uma categoria para liberar campos específicos.</p>
                </div>

                {{-- Computadores --}}
                <div id="div-computador" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph ph-monitor text-blue-600 font-bold"></i>
                            <span class="text-[11px] font-black text-blue-600 uppercase tracking-widest">Hardware</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="processador" placeholder="Processador (ex: i5 12th)" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none focus:border-blue-500">
                            <input type="text" name="memoria" placeholder="Memória (ex: 16GB)" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none focus:border-blue-500">
                            <input type="text" name="geracao" placeholder="Geração/Detalhes" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Impressoras --}}
                <div id="div-impressora" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="bg-indigo-50/50 p-6 rounded-3xl border border-indigo-100 space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph ph-printer text-indigo-600 font-bold"></i>
                            <span class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">Configurações de Impressão</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="tipo_impressora" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none">
                                <option value="">Tipo...</option>
                                <option value="Mono">Monocromática</option>
                                <option value="Color">Colorida</option>
                            </select>
                            <input type="text" name="tipo_papel" placeholder="Papel Suportado (ex: A4, A3)" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none">
                        </div>
                    </div>
                </div>

                {{-- Insumos --}}
                <div id="div-insumo" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="bg-amber-50/50 p-6 rounded-3xl border border-amber-100 space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph ph-drop text-amber-600 font-bold"></i>
                            <span class="text-[11px] font-black text-amber-600 uppercase tracking-widest">Detalhes do Insumo</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select name="cor" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none">
                                <option value="Não se aplica">Cor do Toner/Tinta...</option>
                                <option value="Preto">Preto</option>
                                <option value="Ciano">Ciano</option>
                                <option value="Magenta">Magenta</option>
                                <option value="Amarelo">Amarelo</option>
                            </select>
                            <select name="situacao_insumo" class="bg-white border-slate-200 rounded-xl p-4 text-sm font-bold outline-none">
                                <option value="Original">Original</option>
                                <option value="Compativel">Compatível</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="tipo" id="input-tipo" value="equipamento">

            <div class="flex flex-col md:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-black uppercase text-xs tracking-[0.2em] py-5 rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                    Finalizar Cadastro
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('select-categoria').addEventListener('change', function() {
        const catNome = this.options[this.selectedIndex].getAttribute('data-nome') || '';
        const tipoHidden = document.getElementById('input-tipo');
        const placeholder = document.getElementById('placeholder-text');
        
        const divs = ['div-computador', 'div-impressora', 'div-insumo'];
        divs.forEach(id => document.getElementById(id).classList.add('hidden'));
        placeholder.classList.remove('hidden');

        if (catNome === '') return;

        placeholder.classList.add('hidden');

        if (catNome.includes('Computador') || catNome.includes('Notebook')) {
            document.getElementById('div-computador').classList.remove('hidden');
            tipoHidden.value = 'equipamento';
        } 
        else if (catNome.includes('Impressora')) {
            document.getElementById('div-impressora').classList.remove('hidden');
            tipoHidden.value = 'equipamento';
        }
        else if (catNome.includes('Toner') || catNome.includes('Insumo') || catNome.includes('Suprimento')) {
            document.getElementById('div-insumo').classList.remove('hidden');
            tipoHidden.value = 'insumo';
        }
    });
</script>
@endsection