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
                // Note o uso de 'ilike' para ambos para garantir case-insensitivity no PostgreSQL
                $q->where('nome', 'ilike', '%' . $request->search . '%')
                    ->orWhere('fabricante', 'ilike', '%' . $request->search . '%');
            });
        }

        // CORREÇÃO: O Select envia 'categoria_id'. Devemos filtrar pela chave estrangeira na tabela 'catalogos'.
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Eager Load da categoria
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
        //
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
            'voltagem'     => 'nullable|string',
            'cor'          => 'nullable|string',
            'tipo_papel'   => 'nullable|string',
            'descricao'    => 'nullable|string',
        ]);

        \App\Models\Catalogo::create($data);

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
