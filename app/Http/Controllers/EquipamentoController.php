<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Categoria;
use App\Models\Catalogo;
use App\Models\Estoque;
use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipamentoController extends Controller
{
    /**
     * Lista todos os equipamentos e categorias.
     */
    public function index(Request $request)
    {
        $query = Equipamento::with(['categoria', 'subcategoria', 'cliente', 'estoque', 'catalogo']);

        // 1. Filtro de Busca (Tombo, Serial ou Nome)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tombo', 'like', "%{$search}%")
                    ->orWhere('serial', 'like', "%{$search}%")
                    ->orWhere('nome', 'like', "%{$search}%");
            });
        }

        // 2. Filtro de Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filtro de Situação
        if ($request->filled('situacao') && $request->situacao !== 'Todas') {
            $query->where('situacao', $request->situacao);
        }

        $equipamentos = $query->latest()->paginate(15)->withQueryString();

        return view('equipamentos.index', compact('equipamentos'));
    }

    /**
     * Método para Processamento em Massa (Estilo Planilha)
     */
    public function storeMass(Request $request)
    {
        // Validação do array de equipamentos
        $request->validate([
            'estoque_id' => 'required|exists:estoques,id',
            'equipamentos' => 'required|array|min:1',
            'equipamentos.*.catalogo_id' => 'required|exists:catalogo,id',
            'equipamentos.*.tombo' => 'required|digits:5|unique:equipamentos,tombo',
            'equipamentos.*.serial' => 'required|string|unique:equipamentos,serial',
            'equipamentos.*.status' => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'equipamentos.*.situacao' => 'required|in:Novo,Revisado,Sucata',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->equipamentos as $item) {
                    $itemCatalogo = Catalogo::findOrFail($item['catalogo_id']);

                    Equipamento::create([
                        'tipo'              => 'equipamento',
                        'catalogo_id'       => $itemCatalogo->id,
                        'categoria_id'      => $itemCatalogo->categoria_id,
                        'nome'              => $itemCatalogo->nome,
                        'tombo'             => $item['tombo'],
                        'serial'            => $item['serial'],
                        'status'            => $item['status'],
                        'situacao'          => $item['situacao'],
                        'estoque_id'        => $request->estoque_id,
                        'cor'               => $itemCatalogo->cor,
                        'data_movimentacao' => now(),
                    ]);
                }
            });

            return redirect()->route('equipamentos.index')
                ->with('success', count($request->equipamentos) . ' equipamentos cadastrados com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['erro' => 'Erro ao processar lote: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Salva um único equipamento ou insumo (Modais individuais).
     */
    public function store(Request $request)
    {
        $regras = [
            'tipo'            => 'required|in:equipamento,insumo',
            'catalogo_id'     => 'required|exists:catalogo,id',
            'status'          => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'situacao'        => 'nullable|in:Novo,Revisado,Sucata',
            'quantidade'      => 'nullable|integer|min:1',
            'tombo'           => $request->tipo === 'equipamento' ? 'required|digits:5|unique:equipamentos,tombo' : 'nullable',
            'serial'          => $request->tipo === 'equipamento' ? 'required|string|unique:equipamentos,serial' : 'nullable',
        ];

        // Lógica de destino (Cliente vs Estoque)
        $cliente_id = in_array($request->status, ['Alugado', 'Reservado']) ? $request->cliente_id : null;
        $estoque_id = !$cliente_id ? $request->estoque_id : null;

        if ($cliente_id) $regras['cliente_id'] = 'required|exists:clientes,id';
        else $regras['estoque_id'] = 'required|exists:estoques,id';

        $data = $request->validate($regras);
        $itemCatalogo = Catalogo::findOrFail($data['catalogo_id']);

        try {
            if ($request->tipo === 'insumo') {
                $qtd = $request->input('quantidade', 1);
                for ($i = 0; $i < $qtd; $i++) {
                    Equipamento::create([
                        'tipo' => 'insumo',
                        'catalogo_id' => $itemCatalogo->id,
                        'categoria_id' => $itemCatalogo->categoria_id,
                        'nome' => $itemCatalogo->nome,
                        'status' => $request->status,
                        'situacao' => $request->situacao,
                        'estoque_id' => $estoque_id,
                        'cliente_id' => $cliente_id,
                        'cor' => $itemCatalogo->cor,
                        'data_movimentacao' => now(),
                    ]);
                }
                $mensagem = "{$qtd} unidades de \"{$itemCatalogo->nome}\" adicionadas!";
            } else {
                Equipamento::create([
                    'tipo' => 'equipamento',
                    'catalogo_id' => $itemCatalogo->id,
                    'categoria_id' => $itemCatalogo->categoria_id,
                    'nome' => $itemCatalogo->nome,
                    'tombo' => $data['tombo'],
                    'serial' => $data['serial'],
                    'status' => $request->status,
                    'situacao' => $request->situacao,
                    'estoque_id' => $estoque_id,
                    'cliente_id' => $cliente_id,
                    'cor' => $itemCatalogo->cor,
                    'data_movimentacao' => now(),
                ]);
                $mensagem = "Equipamento cadastrado com sucesso!";
            }

            return redirect()->back()->with('success', $mensagem);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erro' => 'Erro ao salvar.'])->withInput();
        }
    }

    public function show($id)
    {
        $equipamento = Equipamento::with(['catalogo', 'estoque', 'categoria', 'cliente'])->findOrFail($id);
        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        $categorias = Categoria::orderBy('nome')->get();
        $clientes   = Clientes::whereNull('parent_id')->orderBy('nome')->get();
        $estoques   = Estoque::orderBy('nome')->get();
        $catalogos  = Catalogo::orderBy('nome')->get();

        return view('equipamentos.edit', compact('equipamento', 'categorias', 'clientes', 'estoques', 'catalogos'));
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $regras = [
            'status'   => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'situacao' => 'nullable|in:Novo,Revisado,Sucata',
            'tombo'    => $equipamento->tipo === 'equipamento' ? 'required|digits:5|unique:equipamentos,tombo,' . $equipamento->id : 'nullable',
            'serial'   => $equipamento->tipo === 'equipamento' ? 'required|string|unique:equipamentos,serial,' . $equipamento->id : 'nullable',
        ];

        $data = $request->validate($regras);

        if (in_array($request->status, ['Alugado', 'Reservado'])) {
            $data['cliente_id'] = $request->cliente_id;
            $data['estoque_id'] = null;
        } else {
            $data['cliente_id'] = null;
            $data['estoque_id'] = $request->estoque_id;
        }

        $equipamento->update($data);
        return redirect()->back()->with('success', 'Cadastro atualizado!');
    }

    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return redirect()->back()->with('success', 'Item removido!');
    }
}