@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
        <div class="bg-slate-900 p-6 text-white">
            <h3 class="font-black text-xl uppercase"><i class="ph ph-stack text-amber-400 mr-2"></i> Equipamentos Aguardando Tombamento</h3>
        </div>

        <form action="{{ route('equipamentos.aplicar_tombamento') }}" method="POST">
            @csrf
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-[10px] font-black text-slate-500 uppercase">
                        <th class="px-6 py-3 text-left">Modelo</th>
                        <th class="px-6 py-3 text-left">Nº de Série</th>
                        <th class="px-6 py-3 text-left">Estoque</th>
                        <th class="px-6 py-3 text-left w-48">Definir Tombo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($equipamentos as $e)
                    <tr>
                        <td class="px-6 py-4 text-sm font-bold">{{ $e->catalogo->nome }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-slate-500">{{ $e->serial }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $e->estoque->nome ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <input type="text" name="tombos[{{ $e->id }}]" maxlength="5" placeholder="00000"
                                class="w-full border-slate-200 rounded-lg text-sm font-mono focus:ring-blue-500 focus:border-blue-500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-6 bg-slate-50 border-t flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-blue-200">
                    Salvar Tombamentos
                </button>
            </div>
        </form>
    </div>
</div>
@endsection