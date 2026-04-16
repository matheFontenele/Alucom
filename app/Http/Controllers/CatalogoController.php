<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Catalogo::query();

        // Filtro de Texto (Nome ou Fabricante)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                // Usando 'ilike' para compatibilidade com PostgreSQL no Render
                $q->where('nome', 'ilike', '%' . $request->search . '%')
                    ->orWhere('fabricante', 'ilike', '%' . $request->search . '%');
            });
        }

        // Filtro por Categoria
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Eager Load da categoria e agrupamento para a View
        $itens = $query->with('categoria')->get()->groupBy(function ($item) {
            return $item->categoria ? mb_strtoupper($item->categoria->nome) : 'SEM CATEGORIA';
        });

        $categorias = Categoria::orderBy('nome')->get();

        // Certifique-se que o nome da pasta é 'catalogos' (plural) conforme seu arquivo blade
        return view('catalogos.index', compact('itens', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();

        // Retorna a view 'catalogos/create.blade.php'
        return view('catalogos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'         => 'required|string|max:255',
            'fabricante'   => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'tipo'         => 'required|string', // 'equipamento' ou 'insumo'
            'processador'  => 'nullable|string',
            'memoria'      => 'nullable|string',
            'geracao'      => 'nullable|string',
            'voltagem'     => 'nullable|string',
            'tipo_impressora' => 'nullable|string',
            'cor'          => 'nullable|string',
            'tipo_papel'   => 'nullable|string',
            'descricao'    => 'nullable|string',
        ]);

        Catalogo::create($data);

        return redirect()->route('catalogos.index')
            ->with('success', 'Novo modelo adicionado ao catálogo com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Catalogo $catalogo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalogo $catalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalogo $catalogo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catalogo $catalogo)
    {
        //
    }
}
