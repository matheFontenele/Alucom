@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Gestão de Equipamentos - Alucom</h1>

    <div class="mb-4">
        <button class="bg-blue-900 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            <a href="{{ route('equipamentos.create') }}">
                Novo Equipamento
            </a>
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-[11px] tracking-wider">
                    <th class="py-3 px-6 text-left">Tombo</th>
                    <th class="py-3 px-6 text-left">Nome</th>
                    <th class="py-3 px-6 text-left">Categoria / Sub</th>
                    <th class="py-3 px-6 text-left">Serial</th>
                    <th class="py-3 px-6 text-left">Status / Situação</th>
                    <th class="py-3 px-6 text-left">Localização Atual</th>
                    <th class="py-3 px-6 text-left">Data</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($equipamentos as $equip)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4 px-6 text-sm">{{ $equip->tombo }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $equip->nome }}</td>
                    <td class="py-4 px-6 text-sm">
                        {{ $equip->categoria->nome }}
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-500 text-xs">{{ $equip->subcategoria->nome ?? '-' }}</span>
                    </td>
                    <td class="py-4 px-6 font-mono text-[11px] text-gray-500">{{ $equip->serial ?? 'N/A' }}</td>

                    <td class="py-4 px-6">
                        <div class="flex flex-col">
                            {{-- Status Principal --}}
                            <span class="px-2 py-1 rounded text-[11px] font-bold uppercase tracking-wide w-fit
            {{ $equip->status == 'Disponivel' ? 'bg-emerald-100 text-emerald-800' : '' }}
            {{ $equip->status == 'Alugado' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $equip->status == 'Manutenção' ? 'bg-red-100 text-red-800' : '' }}
            {{ $equip->status == 'Reservado' ? 'bg-purple-100 text-purple-800' : '' }}
            {{ $equip->status == 'Devolução' ? 'bg-amber-100 text-amber-800' : '' }}">
                                {{ $equip->status }}
                            </span>

                            {{-- Sub-status --}}
                            @if($equip->situacao)
                            <span class="text-[10px] text-gray-400 font-medium mt-0.5 ml-0.5 italic">
                                {{ $equip->situacao }}
                            </span>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        @if($equip->cliente)
                        <span class="text-slate-700 text-sm flex items-center gap-1">
                            <i class="ph ph-buildings text-blue-500"></i> {{ $equip->cliente->nome }}
                        </span>
                        @elseif($equip->estoque)
                        <span class="text-blue-600 font-bold text-sm flex items-center gap-1">
                            <i class="ph ph-package"></i> {{ $equip->estoque->nome }}
                        </span>
                        @else
                        <span class="text-gray-400 text-xs italic">Não alocado</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-xs text-gray-400">
                        {{-- Usa a data de criação do registro no sistema --}}
                        {{ $equip->created_at->format('d/m/Y') }}
                    </td>
                    {{-- Coluna de Ações Estilizada --}}
                    <td class="py-4 px-6 text-center">
                        <div class="flex justify-center items-center gap-3">
                            <a href="{{ route('equipamentos.show', $equip->id) }}" class="text-gray-400 hover:text-blue-600 transition" title="Visualizar Detalhes">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('equipamentos.edit', $equip->id) }}" class="text-gray-400 hover:text-amber-500 transition" title="Editar Equipamento">
                                <i class="ph ph-pencil-line text-lg"></i>
                            </a>
                            <form action="{{ route('equipamentos.destroy', $equip->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection