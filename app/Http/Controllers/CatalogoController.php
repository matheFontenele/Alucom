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

        return view('catalogo.index', compact('itens', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('catalogo.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'fabricante' => 'required',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria' => 'nullable',
            'tipo_papel' => 'nullable',
            'tipo_impressao' => 'nullable',
            'voltagem' => 'nullable',
            'processador' => 'nullable',
            'geracao' => 'nullable',
            'memoria' => 'nullable',
            'polegadas' => 'nullable',
            'cor' => 'nullable',
            'tipo_insumo' => 'nullable',
            'descricao' => 'nullable',
        ]);

        Catalogo::create($data);

        return redirect()->route('catalogo.index')->with('success', 'Item cadastrado!');
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
