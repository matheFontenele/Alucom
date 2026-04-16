@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl">
                    Entrada em Massa - Equipamentos
                </h3>
                <p class="text-blue-100 text-sm">Preencha as linhas abaixo como uma planilha para registrar múltiplos itens.</p>
            </div>
            <button type="button" onclick="addRow()" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all">
                <i class="ph ph-plus-circle text-lg"></i> Adicionar Linha
            </button>
        </div>

        <form action="{{ route('equipamentos.store_mass') }}" method="POST" class="p-6">
            @csrf
            
            {{-- ID do estoque de destino --}}
            <input type="hidden" name="estoque_id" value="{{ request('estoque_id') }}">

            <div class="overflow-x-auto border border-slate-100 rounded-xl">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-4 py-3 text-left">Modelo (Catálogo)</th>
                            <th class="px-4 py-3 text-left w-32">Tombo*</th>
                            <th class="px-4 py-3 text-left w-48">Nº Série*</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Situação</th>
                            <th class="px-4 py-3 text-center w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="mass-entry-body" class="bg-white divide-y divide-slate-100">
                        {{-- Linha Inicial --}}
                        <tr class="entry-row hover:bg-slate-50/50 transition-colors">
                            <td class="p-2">
                                <select name="equipamentos[0][catalogo_id]" class="w-full border-none bg-transparent font-bold text-sm focus:ring-0" required>
                                    <option value="">Selecione...</option>
                                    @foreach($modelosCatalogo as $modelo)
                                        <option value="{{ $modelo->id }}">{{ $modelo->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="equipamentos[0][tombo]" maxlength="5" required placeholder="00000" 
                                    class="w-full border-none bg-transparent font-mono font-bold text-sm focus:ring-0">
                            </td>
                            <td class="p-2">
                                <input type="text" name="equipamentos[0][serial]" required placeholder="S/N" 
                                    class="w-full border-none bg-transparent font-bold text-sm focus:ring-0">
                            </td>
                            <td class="p-2">
                                <select name="equipamentos[0][status]" class="w-full border-none bg-transparent font-bold text-sm focus:ring-0 text-emerald-600">
                                    <option value="Disponivel">Disponível</option>
                                    <option value="Manutenção">Manutenção</option>
                                    <option value="Alugado">Alugado</option>
                                    <option value="Reservado">Reservado</option>
                                    <option value="Devolução">Devolução</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="equipamentos[0][situacao]" class="w-full border-none bg-transparent font-bold text-sm focus:ring-0">
                                    <option value="Novo">Novo</option>
                                    <option value="Revisado">Revisado</option>
                                    <option value="Sucata">Sucata</option>
                                </select>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-red-500 transition-colors">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-2 text-slate-400 text-xs font-medium">
                    <i class="ph ph-info"></i>
                    Campos com * são obrigatórios. Tombo deve ter exatamente 5 caracteres.
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all">
                    Finalizar Entrada em Massa
                </button>
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

        // Limpa os valores dos campos na nova linha
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.name = input.name.replace('[0]', `[${rowCount}]`);
        });

        newRow.querySelectorAll('select').forEach(select => {
            select.name = select.name.replace('[0]', `[${rowCount}]`);
            // Se quiser que o status/situação padrão permaneça, não limpe o value aqui
        });

        tbody.appendChild(newRow);
        rowCount++;
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.entry-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('Você deve manter pelo menos uma linha para o cadastro.');
        }
    }
</script>

<style>
    /* Estética de Planilha: Remove bordas internas dos inputs para parecer uma célula */
    input:focus, select:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    .entry-row input, .entry-row select {
        padding: 0.5rem;
    }
</style>
@endsection