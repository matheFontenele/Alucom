@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Gestão de Equipamentos - Alucom</h1>

    {{-- Barra de Filtros --}}

    <div class="bg-white p-4 rounded-t-lg shadow-sm mb-4 border border-gray-200">
        <form action="{{ route('equipamentos.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por Tombo, Serial ou Nome..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                <option value="">Todos os Status</option>
                <option value="Disponivel" {{ request('status') == 'Disponivel' ? 'selected' : '' }}>Disponível</option>
                <option value="Alugado" {{ request('status') == 'Alugado' ? 'selected' : '' }}>Alugado</option>
                <option value="Manutenção" {{ request('status') == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                <option value="Devolução" {{ request('status') == 'Devolução' ? 'selected' : '' }}>Devolução</option>
                <option value="Reservado" {{ request('status') == 'Reservado' ? 'selected' : '' }}>Reservado</option>
            </select>

            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-800 transition shadow">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('equipamentos.index') }}" class="text-gray-500 hover:text-red-600 flex items-center text-sm font-medium transition">
                Limpar
            </a>
            @endif
        </form>
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
                            {{-- Deletar equipamento--}}
                            <form action="{{ route('equipamentos.destroy', $equip->id) }}" method="POST" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete text-gray-400 hover:text-red-600 transition" title="Excluir">
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
<script>
    // 1. Alerta de Confirmação de Exclusão
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.form-delete');

            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });

    // 2. Alerta de Sucesso (após redirecionamento do Controller)
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false,
        confirmButtonColor: '#1e3a8a'
    });
    @endif
</script>
@endsection