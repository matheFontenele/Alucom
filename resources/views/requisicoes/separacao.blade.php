@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-3xl mx-auto">

        {{-- BOTÃO VOLTAR --}}
        <div class="mb-6">
            <a href="{{ route('requisicoes.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-900 flex items-center gap-2 transition-colors">
                <i class="ph ph-arrow-left"></i> Voltar para Lista
            </a>
        </div>

        {{-- CARD PRINCIPAL DE SEPARAÇÃO --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100 mb-6">

            {{-- Cabeçalho do Card --}}
            <div class="bg-amber-500 p-6 flex justify-between items-center border-b border-amber-600/20">
                <div>
                    <h2 class="text-white text-2xl font-bold flex items-center gap-3">
                        <i class="ph ph-package-open text-white/80"></i>
                        Separação de Material
                    </h2>
                    <p class="text-amber-100 text-sm mt-1">
                        Requisição #{{ str_pad($requisicao->id, 4, '0', STR_PAD_LEFT) }} • Cliente: {{ $requisicao->cliente->nome ?? 'Não informado' }}
                    </p>
                </div>
                <i class="ph ph-hand-palm text-6xl text-white/20"></i>
            </div>

            <div class="p-8">
                {{-- Alerta de Estoque Insuficiente (Apenas se não houver NADA) --}}
                @if($tombosDisponiveis->count() === 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl mb-8 flex items-center gap-4 text-red-800">
                    <i class="ph ph-warning-octagon text-4xl"></i>
                    <div>
                        <h4 class="font-bold">Atenção! Sem estoque disponível.</h4>
                        <p class="text-sm">Não há nenhum item disponível em estoque para realizar esta separação no momento.</p>
                    </div>
                </div>
                <div class="pt-6">
                    <a href="{{ route('requisicoes.index') }}" class="w-full bg-gray-100 text-gray-500 py-4 rounded-xl font-bold text-center block">Voltar</a>
                </div>
                @else

                <form action="{{ route('requisicoes.separar.update', $requisicao->id) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Card de Informação do Item e Estoque --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                            <h4 class="text-[10px] font-black uppercase text-gray-400 mb-1">Solicitado</h4>
                            <p class="text-gray-800 text-lg font-bold">{{ $requisicao->quantidade }}x {{ $requisicao->item_descricao }}</p>
                            <p class="text-xs text-gray-500">Categoria: {{ $requisicao->categoria }}</p>
                        </div>
                        <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                            <h4 class="text-[10px] font-black uppercase text-blue-400 mb-1">Disponível em Estoque</h4>
                            <p class="text-blue-900 text-lg font-bold">{{ $tombosDisponiveis->count() }} Unidades</p>
                            <p class="text-xs text-blue-500 italic">Pronto para retirada</p>
                        </div>
                    </div>

                    {{-- LÓGICA DE ATENDIMENTO --}}
                    <div class="bg-amber-50/30 p-6 rounded-2xl border border-amber-100 space-y-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="atendimento_completo" id="atendimento_completo" value="1"
                                {{ $tombosDisponiveis->count() >= $requisicao->quantidade ? 'checked' : 'disabled' }}
                                class="w-6 h-6 text-amber-500 rounded-lg border-gray-300 focus:ring-amber-500 transition-all">
                            <label for="atendimento_completo" class="font-bold text-gray-700 cursor-pointer">
                                Atendimento Completo <span class="text-xs text-gray-400 font-normal">(Baixar todas as {{ $requisicao->quantidade }} unidades)</span>
                            </label>
                        </div>

                        {{-- Input de Quantidade Parcial (Aparece se desmarcar o completo) --}}
                        <div id="campo_quantidade_parcial" class="{{ $tombosDisponiveis->count() >= $requisicao->quantidade ? 'hidden' : '' }} mt-4 pt-4 border-t border-amber-100">
                            <label class="block text-xs font-black text-amber-600 uppercase mb-2">Quantidade a ser atendida agora:</label>
                            <input type="number" name="quantidade_separada" id="quantidade_separada"
                                max="{{ min($requisicao->quantidade, $tombosDisponiveis->count()) }}"
                                min="1"
                                value="{{ min($requisicao->quantidade, $tombosDisponiveis->count()) }}"
                                class="w-full md:w-1/3 bg-white border-gray-200 rounded-xl py-3 px-4 shadow-sm font-bold focus:ring-amber-500 focus:border-amber-500">
                            <p class="text-[11px] text-amber-700 mt-2 flex items-center gap-1">
                                <i class="ph ph-info"></i> O status mudará para "Parcialmente Atendida"
                            </p>
                        </div>
                    </div>

                    {{-- Seleção de Patrimônios (Se aplicável ao seu negócio) --}}
                    <div>
                        <label class="block text-sm font-black text-gray-600 uppercase mb-3 flex items-center gap-2">
                            <i class="ph ph-barcode text-blue-600"></i>
                            Vincular Patrimônio (Opcional):
                        </label>
                        <select name="patrimonio_novo" id="patrimonio_novo"
                            class="w-full bg-slate-50 border-gray-200 rounded-xl py-4 px-5 text-sm font-bold focus:ring-amber-400 focus:border-amber-400 shadow-inner">
                            <option value="">Nenhum patrimônio específico</option>
                            @foreach($tombosDisponiveis as $equip)
                            <option value="{{ $equip->tombo }}">Tombo: {{ $equip->tombo }} - {{ $equip->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dados de Auditoria --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Data da Separação</label>
                            <input type="text" value="{{ now()->format('d/m/Y') }}" disabled class="w-full bg-gray-50 border-none rounded-lg text-xs font-bold text-gray-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Responsável</label>
                            <input type="text" value="{{ auth()->user()->name }}" disabled class="w-full bg-gray-50 border-none rounded-lg text-xs font-bold text-gray-500">
                        </div>
                    </div>

                    {{-- Botão de Ação --}}
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit" class="w-full bg-amber-500 text-white py-5 rounded-2xl font-black uppercase text-sm hover:bg-amber-600 transition-all shadow-lg hover:shadow-amber-500/20 flex items-center justify-center gap-3 tracking-wider">
                            <i class="ph ph-check-circle text-2xl"></i>
                            CONFIRMAR SEPARAÇÃO E ATUALIZAR STATUS
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkCompleto = document.getElementById('atendimento_completo');
        const campoParcial = document.getElementById('campo_quantidade_parcial');
        const inputQtd = document.getElementById('quantidade_separada');

        if (checkCompleto) {
            checkCompleto.addEventListener('change', function() {
                if (this.checked) {
                    campoParcial.classList.add('hidden');
                    // Se for completo, a quantidade é a da requisição
                    inputQtd.value = "{{ $requisicao->quantidade }}";
                } else {
                    campoParcial.classList.remove('hidden');
                }
            });
        }
    });
</script>
@endsection