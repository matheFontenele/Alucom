@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-amber-600 p-4">
            <h2 class="text-white text-lg font-bold">Editar Movimentação #{{ $movimentacao->id }}</h2>
        </div>

        <form action="{{ route('movimentacoes.update', $movimentacao->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Atualizar Situação</label>
                <select name="situacao" class="w-full border-gray-300 rounded-lg">
                    @php
                        $opcoes = [
                            'Aluguel' => ['Aguardando Rota', 'Em Rota', 'No Cliente'],
                            'Devolução' => ['Aguardando Coleta', 'Em Rota', 'Recebido'],
                            'Disponível' => ['Em Estoque'],
                            'Manutenção' => ['Aguardando Retirada', 'Em Laboratório', 'Pronto']
                        ];
                        $lista = $opcoes[$movimentacao->tipo] ?? ['Aguardando Rota', 'Em Rota', 'No Cliente', 'Recebido', 'Em Estoque'];
                    @endphp
                    
                    @foreach($lista as $s)
                        <option value="{{ $s }}" {{ $movimentacao->situacao == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Observações</label>
                <textarea name="observacao" rows="3" class="w-full border-gray-300 rounded-lg">{{ $movimentacao->observacao }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('movimentacoes.index') }}" class="px-6 py-2 border rounded-lg text-gray-600">Cancelar</a>
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection