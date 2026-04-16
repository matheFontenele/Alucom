@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        {{-- Cabeçalho --}}
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl flex items-center gap-2">
                    <i class="ph ph-drop text-emerald-400"></i>
                    Entrada em Massa - Insumos
                </h3>
                <p class="text-blue-100 text-sm">Registre múltiplos insumos e suprimentos definindo a quantidade de cada lote.</p>
            </div>
            <button type="button" onclick="addRow()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-emerald-900/20">
                <i class="ph ph-plus-circle text-lg"></i> Adicionar Linha
            </button>
        </div>

        <form action="{{ route('equipamentos.store_mass') }}" method="POST" class="p-6">
            @csrf

            {{-- Campos de Controle --}}
            <input type="hidden" name="tipo_entrada" value="insumo">
            <input type="hidden" name="estoque_id" value="{{ $estoque_id }}">

            <div class="overflow-x-auto border border-slate-100 rounded-xl">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-4 py-3 text-left">Insumo (Catálogo)</th>
                            <th class="px-4 py-3 text-left w-48">Cor</th>
                            <th class="px-4 py-3 text-left w-32">Quantidade</th>
                            <th class="px-4 py-3 text-left w-40">Situação</th>
                            <th class="px-4 py-3 text-center w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="mass-entry-body" class="bg-white divide-y divide-slate-100">
                        {{-- Linha Protótipo --}}
                        <tr class="entry-row hover:bg-slate-50/50 transition-colors">
                            <td class="p-2">
                                <select name="itens[0][catalogo_id]" required>
                                    <option value="">Selecione o item...</option>
                                    @foreach($modelosCatalogo as $modelo)
                                    <option value="{{ $modelo->id }}">
                                        {{ $modelo->nome }} ({{ $modelo->categoria->nome ?? 'Sem Categoria' }})
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="itens[0][cor]" class="w-full border-none bg-transparent font-bold text-sm focus:ring-0">
                                    <option value="Não se Aplica" selected>Não se Aplica</option>
                                    <option value="Preto">Preto</option>
                                    <option value="Ciano">Ciano</option>
                                    <option value="Magenta">Magenta</option>
                                    <option value="Amarelo">Amarelo</option>
                                    <option value="Mono">Mono</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="number" name="itens[0][quantidade]" min="1" value="1" required
                                    class="w-full border-none bg-transparent font-bold text-sm focus:ring-0 text-blue-600">
                            </td>
                            <td class="p-2">
                                <select name="itens[0][situacao]" required class="...">
                                    <option value="Original">Original</option>
                                    <option value="Compativel">Compatível</option>
                                    <option value="Recondicionado">Recondicionado</option>
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

            {{-- Rodapé de Ações --}}
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-2 text-slate-400 text-xs font-medium">
                    <i class="ph ph-info"></i>
                    Insumos serão registrados individualmente no banco com base na quantidade informada.
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('estoques.show', $estoque_id) }}" class="px-8 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-12 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all">
                        Registrar Insumos
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

        // Atualiza os nomes dos campos para o novo índice
        newRow.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace('[0]', `[${rowCount}]`);
            // Reseta a quantidade para 1 em novas linhas
            if (el.type === 'number') el.value = 1;
        });

        tbody.appendChild(newRow);
        rowCount++;
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.entry-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('Mantenha pelo menos uma linha para o registro.');
        }
    }
</script>

<style>
    /* Estilo Planilha */
    input:focus,
    select:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .entry-row input,
    .entry-row select {
        padding: 0.5rem;
    }
</style>
@endsection