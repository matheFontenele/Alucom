@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden border-2 border-green-600">
        {{-- Cabeçalho de Destaque --}}
        <div class="bg-green-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold uppercase tracking-tight">Área de Separação</h1>
                    <p class="text-green-100 opacity-90">Requisição #{{ str_pad($requisicao->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <i class="ph ph-package text-5xl opacity-30"></i>
            </div>
        </div>

        {{-- Resumo da Solicitação --}}
        <div class="p-6 bg-gray-50 border-b flex justify-between items-center text-sm">
            <div>
                <span class="text-gray-500 uppercase font-bold text-[10px]">Item Solicitado:</span>
                <p class="text-gray-800 font-bold text-lg">{{ $requisicao->item->nome ?? 'Item não identificado' }}</p>
            </div>
            <div class="text-right">
                <span class="text-gray-500 uppercase font-bold text-[10px]">Qtd. Solicitada:</span>
                <p class="text-green-700 font-black text-2xl">{{ $requisicao->quantidade }}</p>
            </div>
        </div>

        <form action="{{ route('requisicoes.separar.update', $requisicao->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Quantidade Separada --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Quantidade Separada</label>
                    <div class="relative">
                        <input type="number" name="quantidade_separada" value="{{ $requisicao->quantidade }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-0 text-xl font-bold">
                        <span class="absolute right-4 top-3 text-gray-400">UN</span>
                    </div>
                </div>

                {{-- Data da Separação --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Data da Separação</label>
                    <input type="date" name="data_separacao" value="{{ date('Y-m-d') }}"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500">
                </div>
            </div>

            {{-- Separado Por --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Separado por (Estoque)</label>
                <select name="separado_por" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 font-medium" required>
                    <option value="">Selecione o Responsável</option>
                    <option value="João Silva">João Silva (Estoque)</option>
                    <option value="Maria Oliveira">Maria Oliveira (Estoque)</option>
                    <option value="Fontenele">Fontenele (Admin)</option>
                </select>
            </div>

            {{-- Campo de Patrimônio Novo (Aparece apenas se for Substituição) --}}
            <div class="bg-blue-50 p-5 rounded-xl border-2 border-blue-200">
                <label class="block text-sm font-bold text-blue-900 mb-2">
                    <i class="ph ph-barcode font-bold"></i> SELECIONE O TOMBO DO NOVO EQUIPAMENTO
                </label>
                <select name="patrimonio_novo" class="w-full border-2 border-blue-300 rounded-xl px-4 py-3 focus:border-blue-600 font-bold text-blue-900" required>
                    <option value="">Buscar tombo disponível no estoque...</option>
                    @foreach($tombosDisponiveis as $equip)
                        <option value="{{ $equip->tombo }}">
                            TOMBO: {{ $equip->tombo }} | {{ $equip->nome }} {{ $equip->cor ? "($equip->cor)" : "" }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-blue-700 mt-2 italic font-medium">
                    * Exibindo apenas itens com status "Disponivel" vinculados a este modelo.
                </p>
            </div>

            {{-- Baixa no Sistema --}}
            <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                <label class="block text-sm font-bold text-amber-800 mb-2 italic text-center uppercase tracking-widest">Dar Baixa no Sistema?</label>
                <div class="flex justify-center gap-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="baixa_sistema" value="1" class="w-5 h-5 text-green-600">
                        <span class="ml-2 font-bold text-gray-700">SIM</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="baixa_sistema" value="0" checked class="w-5 h-5 text-red-600">
                        <span class="ml-2 font-bold text-gray-700">NÃO</span>
                    </label>
                </div>
            </div>

            {{-- Observação --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Observação do Estoque</label>
                <textarea name="observacao_separacao" rows="3" placeholder="Ex: Item com etiqueta Moreia aplicada."
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="{{ route('requisicoes.index') }}" class="flex-1 text-center py-4 border-2 border-gray-200 rounded-xl font-bold text-gray-500 hover:bg-gray-50 transition">
                    Voltar
                </a>
                <button type="submit" class="flex-[2] bg-green-600 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition transform hover:scale-[1.02]">
                    Confirmar Separação
                </button>
            </div>
        </form>
    </div>
</div>
@endsection