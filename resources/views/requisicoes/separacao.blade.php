{{-- Alerta de Estoque Vazio --}}
@if($estoqueVazio)
<div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl mb-6">
    <div class="flex items-center gap-4">
        <i class="ph ph-warning-octagon text-3xl text-red-600"></i>
        <div>
            <h3 class="text-red-800 font-bold text-lg">Item Indisponível no Estoque!</h3>
            <p class="text-red-700">Não encontramos o item <strong>{{ $requisicao->item_descricao }}</strong> no {{ $requisicao->estoque->nome }}.</p>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        {{-- Botão que futuramente chamará sua API de Compras --}}
        <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700 transition">
            SOLICITAR PEDIDO DE COMPRA
        </button>
        <a href="{{ route('requisicoes.index') }}" class="text-gray-600 px-4 py-2 font-semibold">Cancelar</a>
    </div>
</div>
@else
{{-- Formulário normal de separação que você já tem --}}
<form action="{{ route('requisicoes.separar.update', $requisicao->id) }}" method="POST">
    @csrf
    {{-- Select de Tombos, Observação, etc --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <label class="block text-sm font-bold text-gray-700 mb-2">Selecione o Patrimônio para Baixa:</label>
        <select name="patrimonio_novo" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach($tombosDisponiveis as $equip)
            <option value="{{ $equip->tombo }}">{{ $equip->tombo }} - {{ $equip->nome }}</option>
            @endforeach
        </select>

        <input type="hidden" name="baixa_sistema" value="1">
        <input type="hidden" name="quantidade_separada" value="{{ $requisicao->quantidade }}">
        <input type="hidden" name="data_separacao" value="{{ now()->format('Y-m-d') }}">

        <button type="submit" class="mt-6 w-full bg-green-600 text-white py-4 rounded-xl font-bold hover:bg-green-700 transition shadow-lg">
            CONFIRMAR SEPARAÇÃO E DAR BAIXA
        </button>
    </div>
</form>
@endif