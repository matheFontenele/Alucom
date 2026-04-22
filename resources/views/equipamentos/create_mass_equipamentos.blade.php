@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        {{-- Cabeçalho Estilo Dashboard --}}
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl uppercase tracking-tight">
                    <i class="ph ph-barcode text-blue-400 mr-2"></i> Entrada em Massa de Equipamentos
                </h3>
                <p class="text-slate-400 text-sm">Os atributos técnicos (Processador, RAM, etc.) são herdados automaticamente do Catálogo.</p>
            </div>
            <button type="button" onclick="addRow()" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-900/20">
                <i class="ph ph-plus-bold"></i> Adicionar Linha
            </button>
        </div>

        <form action="{{ route('equipamentos.store_mass') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="estoque_id" value="{{ request('estoque_id') }}">

            <div class="overflow-x-auto border border-slate-100 rounded-2xl bg-slate-50/30">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100/50">
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-4 py-4 text-left">Modelo do Catálogo</th>
                            <th class="px-4 py-4 text-left w-32">Tombo (5 dgt)</th>
                            <th class="px-4 py-4 text-left w-48">Nº de Série</th>
                            <th class="px-4 py-4 text-left w-40">Status</th>
                            <th class="px-4 py-4 text-left w-32">Situação</th>
                            <th class="px-4 py-4 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="mass-entry-body" class="divide-y divide-slate-100 bg-white">
                        <tr class="entry-row">
                            <td class="p-1">
                                <select name="equipamentos[0][catalogo_id]" required class="w-full border-none bg-transparent font-bold text-sm focus:ring-0">
                                    <option value="">Selecione um modelo...</option>
                                    @foreach($modelosCatalogo as $modelo)
                                        @if(!$modelo->ehInsumo())
                                        <option value="{{ $modelo->id }}">{{ $modelo->nome }} ({{ $modelo->fabricante }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-1">
                                <input type="text" name="equipamentos[0][tombo]" maxlength="5" required placeholder="00000" 
                                    class="w-full border-none bg-transparent font-mono font-bold text-sm focus:ring-0 text-blue-600 placeholder:text-slate-300">
                            </td>
                            <td class="p-1">
                                <input type="text" name="equipamentos[0][serial]" required placeholder="S/N" 
                                    class="w-full border-none bg-transparent font-bold text-sm focus:ring-0 placeholder:text-slate-300">
                            </td>
                            <td class="p-1">
                                <select name="equipamentos[0][status]" class="w-full border-none bg-transparent font-bold text-xs focus:ring-0">
                                    <option value="Disponivel">🟢 Disponível</option>
                                    <option value="Manutenção">🟠 Manutenção</option>
                                    <option value="Alugado">🔴 Alugado</option>
                                </select>
                            </td>
                            <td class="p-1">
                                <select name="equipamentos[0][situacao]" class="w-full border-none bg-transparent font-bold text-xs focus:ring-0">
                                    <option value="Novo">Novo</option>
                                    <option value="Revisado">Revisado</option>
                                    <option value="Sucata">Sucata</option>
                                </select>
                            </td>
                            <td class="p-1 text-center">
                                <button type="button" onclick="removeRow(this)" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:bg-red-50 hover:text-red-500 transition-all">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-3 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <i class="ph ph-info text-lg text-blue-500"></i>
                    Tombos e Seriais devem ser únicos no sistema.
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('estoques.show', request('estoque_id')) }}" class="px-8 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-all">Cancelar</a>
                    <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white px-10 py-3 rounded-xl font-bold shadow-xl transition-all">
                        Finalizar Registro
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let rowCount = 1;

    function addRow() {
        const tbody = document.getElementById('mass-entry-body');
        const firstRow = tbody.querySelector('.entry-row');
        const newRow = firstRow.cloneNode(true);

        // Limpa valores e atualiza o index [x] nos nomes dos campos
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
        });

        newRow.querySelectorAll('select').forEach(select => {
            select.name = select.name.replace(/\[\d+\]/, `[${rowCount}]`);
        });

        tbody.appendChild(newRow);
        rowCount++;
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.entry-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('A planilha deve conter ao menos um item.');
        }
    }
</script>
@endsection