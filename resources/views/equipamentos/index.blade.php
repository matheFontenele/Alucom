@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Gestão de Equipamentos - Alucom</h1>

    <div class="mb-4">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Novo Equipamento</button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Tombo</th>
                    <th class="py-3 px-6 text-left">Nome</th>
                    <th class="py-3 px-6 text-left">Categoria / Sub</th>
                    <th class="py-3 px-6 text-left">Serial</th>
                    <th class="py-3 px-6 text-left">Situação</th>
                    <th class="py-3 px-6 text-left">Cliente</th>
                    <th class="py-3 px-6 text-left">Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipamentos as $equip)
                <tr class="border-b">
                    <td class="py-4 px-6">{{ $equip->tombo }}</td>
                    <td class="py-4 px-6 font-bold">{{ $equip->nome }}</td>
                    <td class="py-4 px-6 text-sm">
                        {{ $equip->categoria->nome }}
                        <span class="text-gray-400">/</span>
                        {{ $equip->subcategoria->nome ?? '-' }}
                    </td>
                    <td class="py-4 px-6">{{ $equip->serial ?? 'N/A' }}</td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-1 rounded text-xs 
                            {{ $equip->situacao == 'Disponivel' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                            {{ $equip->situacao }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        @if($equip->cliente)
                        {{ $equip->cliente->nome }}
                        @elseif($equip->estoque)
                        {{-- Se não tem cliente, mas tem estoque vinculado --}}
                        <span class="text-blue-600 font-bold">{{ $equip->estoque->nome }}</span>
                        @else
                        {{-- Caso de segurança se ambos forem nulos --}}
                        <span class="text-gray-400 italic">Não alocado</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm">{{ $equip->data_movimentacao ? $equip->data_movimentacao->format('d/m/Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection