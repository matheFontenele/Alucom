@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        {{-- Cabeçalho Azul --}}
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl">Novo Equipamento</h3>
                <p class="text-blue-100 text-sm">Registre um novo item no inventário.</p>
            </div>
            <i class="ph ph-cpu text-3xl"></i>
        </div>

        <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Nome do Equipamento --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Nome / Modelo</label>
                    <input type="text" name="nome" placeholder="Ex: Impressora HP M404dw" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Tombo --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Tombo (Max 5)</label>
                    <input type="text" name="tombo" maxlength="5" placeholder="00000" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Serial --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Número de Série</label>
                    <input type="text" name="serial" placeholder="S/N" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Categoria --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Subcategoria --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Subcategoria</label>
                    <select name="subcategoria_id" id="subcategoria_id" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500" disabled>
                        <option value="">Aguardando categoria...</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Status Inicial</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="Disponivel">Disponível (Estoque)</option>
                        <option value="Alugado">Alugado (Cliente)</option>
                        <option value="Manutenção">Em Manutenção</option>
                        <option value="Reservado">Reservado</option>
                    </select>
                </div>

                {{-- Localização Atual (Estoque ou Cliente) --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Está em qual Estoque?</label>
                    <select name="estoque_id" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhum (Está em trânsito/cliente)</option>
                        @foreach($estoques as $est)
                        <option value="{{ $est->id }}">{{ $est->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Vincular a Cliente/Unidade</label>
                    <select name="cliente_id" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 font-bold text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhum (Está no estoque)</option>
                        @foreach($clientes as $cli)
                        <option value="{{ $cli->id }}">{{ $cli->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all">
                    Cadastrar Equipamento
                </button>
            </div>
        </form>
    </div>
</div>

<!--Script JS para categoria e subcategoria-->
<script>
document.getElementById('categoria_id').addEventListener('change', function() {
    const categoriaId = this.value;
    const subSelect = document.getElementById('subcategoria_id');

    // Limpa o select de subcategorias
    subSelect.innerHTML = '<option value="">Carregando...</option>';
    subSelect.disabled = true;

    if (!categoriaId) {
        subSelect.innerHTML = '<option value="">Selecione uma categoria primeiro</option>';
        return;
    }

    // Busca as subcategorias via API
    fetch(`/api/categorias/${categoriaId}/subcategorias`)
        .then(response => response.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">Selecione a subcategoria</option>';
            data.forEach(sub => {
                subSelect.innerHTML += `<option value="${sub.id}">${sub.nome}</option>`;
            });
            subSelect.disabled = false;
        })
        .catch(error => {
            console.error('Erro ao buscar subcategorias:', error);
            subSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        });
});
</script>
@endsection