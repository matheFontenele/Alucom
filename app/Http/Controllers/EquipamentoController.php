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
     * Lista todos os equipamentos e categorias com busca e filtros.
     */
    public function index(Request $request)
    {
        $query = Equipamento::with(['categoria', 'subcategoria', 'cliente', 'estoque', 'catalogo']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('equipamentos.tombo', 'like', "%{$search}%")
                    ->orWhere('equipamentos.serial', 'like', "%{$search}%")
                    ->orWhere('equipamentos.nome', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('equipamentos.status', $request->status);
        }

        if ($request->filled('situacao') && $request->situacao !== 'Todas') {
            $query->where('equipamentos.situacao', $request->situacao);
        }

        $equipamentos = $query->latest()->paginate(15)->withQueryString();
        return view('equipamentos.index', compact('equipamentos'));
    }

    /**
     * Tela de Entrada em Massa - Equipamentos
     */
    public function massEntry(Request $request)
    {
        $estoque_id = $request->estoque_id;
        $tipo = 'equipamento';

        $modelos = Catalogo::where('tipo', 'equipamento')
            ->with('categoria')
            ->orderBy('nome')
            ->get();

        return view('equipamentos.create_mass_equipamentos', compact('modelos', 'estoque_id', 'tipo'));
    }

    /**
     * Tela de Entrada em Massa - Insumos
     */
    public function massEntryInsumo(Request $request)
    {
        $estoque_id = $request->estoque_id;
        $tipo = 'insumo';

        $modelos = Catalogo::where('tipo', 'insumo')
            ->with('categoria')
            ->orderBy('nome')
            ->get();

        return view('equipamentos.create_mass_insumos', compact('modelos', 'estoque_id', 'tipo'));
    }

    /**
     * Processamento em Massa Unificado
     */
    public function storeMass(Request $request)
    {
        // 1. Validação rigorosa
        $validated = $request->validate([
            'estoque_id' => 'required|exists:estoques,id',
            'equipamentos' => 'required|array|min:1',
            'equipamentos.*.catalogo_id' => 'required|exists:catalogo,id',
            'equipamentos.*.tombo' => 'required|size:5|distinct|unique:equipamentos,tombo',
            'equipamentos.*.serial' => 'required|distinct|unique:equipamentos,serial',
            'equipamentos.*.status' => 'required|string',
            'equipamentos.*.situacao' => 'required|string',
        ], [
            'equipamentos.*.tombo.unique' => 'Um dos números de tombo já existe no banco.',
            'equipamentos.*.tombo.distinct' => 'Você digitou tombos duplicados na mesma lista.',
            'equipamentos.*.serial.unique' => 'Um dos números de série já existe no banco.',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->equipamentos as $item) {
                Equipamento::create([
                    'catalogo_id' => $item['catalogo_id'],
                    'estoque_id'  => $request->estoque_id,
                    'tombo'       => $item['tombo'],
                    'serial'      => $item['serial'],
                    'status'      => $item['status'],
                    'situacao'    => $item['situacao'],
                ]);
            }

            DB::commit();
            return redirect()->route('estoques.show', $request->estoque_id)
                ->with('success', count($request->equipamentos) . ' itens registrados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Falha ao processar entrada: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Salva um único item (Modal ou Individual)
     */
    public function store(Request $request)
    {
        $regras = [
            'tipo'        => 'required|in:equipamento,insumo',
            'catalogo_id' => 'required|exists:catalogo,id',
            'status'      => 'required|in:Alugado,Reservado,Devolução,Liberado,Manutenção',
            'situacao'    => 'nullable|in:Novo,Revisado,Sucata',
            'quantidade'  => 'nullable|integer|min:1',
            'tombo'       => $request->tipo === 'equipamento' ? 'required|digits:5|unique:equipamentos,tombo' : 'nullable',
            'serial'      => $request->tipo === 'equipamento' ? 'required|string|unique:equipamentos,serial' : 'nullable',
        ];

        $data = $request->validate($regras);
        $itemCatalogo = Catalogo::findOrFail($data['catalogo_id']);

        $cliente_id = in_array($request->status, ['Alugado', 'Reservado']) ? $request->cliente_id : null;
        $estoque_id = !$cliente_id ? $request->estoque_id : null;

        try {
            $qtd = ($request->tipo === 'insumo') ? ($request->quantidade ?? 1) : 1;

            for ($i = 0; $i < $qtd; $i++) {
                Equipamento::create([
                    'tipo'              => $request->tipo,
                    'catalogo_id'       => $itemCatalogo->id,
                    'categoria_id'      => $itemCatalogo->categoria_id,
                    'nome'              => $itemCatalogo->nome,
                    'tombo'             => $data['tombo'] ?? null,
                    'serial'            => $data['serial'] ?? null,
                    'status'            => $request->status,
                    'situacao'          => $request->situacao,
                    'estoque_id'        => $estoque_id,
                    'cliente_id'        => $cliente_id,
                    'cor'               => $itemCatalogo->cor,
                    'data_movimentacao' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Cadastro realizado!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erro' => 'Erro ao salvar: ' . $e->getMessage()])->withInput();
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
