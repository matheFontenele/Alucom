@extends('layouts.app')

@section('subtitle', 'Gerenciamento / Usuários')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Controle de Acesso</h1>
        <p class="text-slate-500 text-sm">Gerencie os colaboradores e suas funções no sistema</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold hover:bg-slate-700 transition flex items-center gap-2 shadow-lg shadow-slate-900/10">
        <i class="ph ph-user-plus text-xl"></i> Novo Usuário
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Colaborador</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">E-mail</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Função</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($usuarios as $u)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        <span class="font-bold text-slate-700">{{ $u->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-500 text-sm font-medium">{{ $u->email }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter
                        {{ $u->funcao == 'Motorista' ? 'bg-blue-100 text-blue-600' : 
                          ($u->funcao == 'Gerência' ? 'bg-purple-100 text-purple-600' : 'bg-slate-100 text-slate-600') }}">
                        {{ $u->funcao }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('usuarios.edit', $u->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition">
                            <i class="ph ph-pencil-simple text-xl"></i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Deseja remover este usuário?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-400 hover:text-red-600 transition">
                                <i class="ph ph-trash text-xl"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection