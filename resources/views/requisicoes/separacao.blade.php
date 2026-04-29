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
                    <p class="text-amber-100 text-sm mt-1">Requisição #{{ str_pad($requisicao->id, 4, '0', STR_PAD_LEFT) }} • Cliente: {{ $requisicao->cliente->nome ?? 'Não informado' }}</p>
                </div>

                {{-- Ícone Grande Decorativo --}}
                <i class="ph ph-hand-palm text-6xl text-white/20"></i>
            </div>

            <div class="p-8">
                {{-- Alerta de Estoque Vazio (Se houver essa lógica futuramente) --}}
                @if(isset($estoqueVazio) && $estoqueVazio)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl mb-8 flex items-center gap-4 text-red-800">
                    <i class="ph ph-warning-octagon text-4xl"></i>
                    <div>
                        <h4 class="font-bold">Atenção! Sem estoque disponível.</h4>
                        <p class="text-sm">Não há patrimônios deste item em estoque para separação.</p>
                    </div>
                </div>
                @else
                <form action="{{ route('requisicoes.separar.update', $requisicao->id) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Informações do Item --}}
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <h4 class="text-xs font-black uppercase text-gray-400 mb-2">Item Solicitado</h4>
                        <p class="text-gray-800 text-xl font-bold">{{ $requisicao->quantidade }}x {{ $requisicao->item_descricao }}</p>
                        <p class="text-gray-500 text-sm">Categoria: {{ $requisicao->categoria }}</p>
                    </div>

                    {{-- Campo de Seleção do Patrimônio --}}
                    <div>
                        <label for="patrimonio_novo" class="block text-sm font-black text-gray-600 uppercase mb-3 flex items-center gap-2">
                            <i class="ph ph-barcode text-blue-600"></i>
                            Selecione o Patrimônio para Baixa:
                        </label>
                        <select name="patrimonio_novo" id="patrimonio_novo" required
                            class="w-full bg-slate-50 border-gray-200 rounded-xl py-4 px-5 text-sm font-bold focus:ring-amber-400 focus:border-amber-400 shadow-inner">
                            <option value="" disabled selected>-- Escolha um patrimônio disponível --</option>
                            @foreach($tombosDisponiveis as $equip)
                            <option value="{{ $equip->tombo }}">Tombo: {{ $equip->tombo }} - {{ $equip->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Campos Ocultos para o Update --}}
                    <input type="hidden" name="baixa_sistema" value="1">
                    <input type="hidden" name="quantidade_separada" value="{{ $requisicao->quantidade }}">
                    <input type="hidden" name="data_separacao" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="separado_por" value="{{ auth()->user()->name }}">

                    {{-- Botão de Ação Principal --}}
                    <div class="pt-6 border-t border-gray-100 mt-8">
                        <button type="submit" class="w-full bg-amber-500 text-white py-5 rounded-2xl font-black uppercase text-sm hover:bg-amber-600 transition-all shadow-lg hover:shadow-amber-500/20 flex items-center justify-center gap-3 tracking-wider">
                            <i class="ph ph-check-circle text-2xl"></i>
                            CONFIRMAR SEPARAÇÃO E DAR BAIXA NO ESTOQUE
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection