@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Gestão de Equipamentos - Alucom</h1>
        <div class="flex gap-2">
            </div>
    </div>

    {{-- Barra de Filtros Reformulada --}}
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-200">
        <form action="{{ route('equipamentos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">
            
            {{-- Busca Geral --}}
            <div class="flex flex-col gap-1 lg:col-span-2">
                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pesquisar</label>
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tombo, Serial ou Nome..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>

            {{-- Filtro Status --}}
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none font-medium appearance-none">
                    <option value="">Todos os Status</option>
                    @foreach(['Alugado', 'Reservado', 'Devolução', 'Liberado', 'Manutenção'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro Situação --}}
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Situação</label>
                <select name="situacao" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none font-medium appearance-none">
                    <option value="">Todas</option>
                    <option value="Novo" {{ request('situacao') == 'Novo' ? 'selected' : '' }}>Novo</option>
                    <option value="Revisado" {{ request('situacao') == 'Revisado' ? 'selected' : '' }}>Revisado</option>
                    <option value="Sucata" {{ request('situacao') == 'Sucata' ? 'selected' : '' }}>Sucata</option>
                </select>
            </div>

            {{-- Botões --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-800 transition shadow flex-1">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status', 'situacao']))
                    <a href="{{ route('equipamentos.index') }}" class="bg-gray-100 text-gray-600 p-2 rounded-xl hover:bg-red-50 hover:text-red-600 transition flex items-center justify-center" title="Limpar Filtros">
                        <i class="ph ph-trash-simple text-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest border-b border-gray-200">
                    <th class="py-4 px-6 text-left">Tombo</th>
                    <th class="py-4 px-6 text-left">Equipamento</th>
                    <th class="py-4 px-6 text-left">Identificação</th>
                    <th class="py-4 px-6 text-left">Status / Situação</th>
                    <th class="py-4 px-6 text-left">Localização Atual</th>
                    <th class="py-4 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($equipamentos as $equip)
                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors">
                    {{-- Tombo com destaque --}}
                    <td class="py-4 px-6">
                        <span class="bg-slate-100 text-slate-700 font-mono font-bold px-2 py-1 rounded border border-slate-200 text-sm">
                            {{ $equip->tombo ?? '---' }}
                        </span>
                    </td>
                    
                    {{-- Nome e Categoria --}}
                    <td class="py-4 px-6">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-800">{{ $equip->nome }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                {{ $equip->categoria->nome }} 
                                @if($equip->subcategoria) / {{ $equip->subcategoria->nome }} @endif
                            </span>
                        </div>
                    </td>

                    {{-- Serial --}}
                    <td class="py-4 px-6 font-mono text-xs text-gray-500">
                        {{ $equip->serial ?? 'N/A' }}
                    </td>

                    {{-- Status / Situação --}}
                    <td class="py-4 px-6">
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide w-fit
                                {{ $equip->status == 'Liberado' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $equip->status == 'Alugado' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $equip->status == 'Manutenção' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $equip->status == 'Reservado' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $equip->status == 'Devolução' ? 'bg-amber-100 text-amber-700' : '' }}">
                                {{ $equip->status }}
                            </span>
                            @if($equip->situacao)
                                <span class="text-[10px] text-slate-400 font-medium ml-1">
                                    ● {{ $equip->situacao }}
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- Localização Dinâmica --}}
                    <td class="py-4 px-6">
                        @if(in_array($equip->status, ['Alugado', 'Reservado']) && $equip->cliente)
                            <div class="flex flex-col">
                                <span class="text-blue-700 font-bold text-sm flex items-center gap-1">
                                    <i class="ph ph-buildings"></i> {{ $equip->cliente->nome }}
                                </span>
                                <span class="text-[10px] text-gray-400 italic font-medium">No Cliente</span>
                            </div>
                        @elseif($equip->estoque)
                            <div class="flex flex-col">
                                <span class="text-slate-700 font-semibold text-sm flex items-center gap-1">
                                    <i class="ph ph-package"></i> {{ $equip->estoque->nome }}
                                </span>
                                <span class="text-[10px] text-emerald-600 font-medium">Em Estoque</span>
                            </div>
                        @else
                            <span class="text-gray-300 text-xs italic">Não alocado</span>
                        @endif
                    </td>

                    {{-- Ações --}}
                    <td class="py-4 px-6">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('equipamentos.show', $equip->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Ver">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('equipamentos.edit', $equip->id) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                <i class="ph ph-pencil-line text-lg"></i>
                            </a>
                            <form action="{{ route('equipamentos.destroy', $equip->id) }}" method="POST" class="form-delete inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Excluir">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-gray-400 italic">
                        Nenhum equipamento encontrado com esses filtros.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script de Alertas e Confirmações --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-delete');
            Swal.fire({
                title: 'Excluir Equipamento?',
                text: "Esta ação removerá o item permanentemente do sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Pronto!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
    @endif
</script>
@endsection