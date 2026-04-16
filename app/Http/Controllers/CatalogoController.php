<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Catalogo::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'ilike', '%' . $request->search . '%')
                  ->orWhere('fabricante', 'ilike', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $itens = $query->with('categoria')->get()->groupBy(function ($item) {
            return $item->categoria ? mb_strtoupper($item->categoria->nome) : 'SEM CATEGORIA';
        });

        $categorias = Categoria::orderBy('nome')->get();

        // Pasta: resources/views/catalogo/index.blade.php
        return view('catalogo.index', compact('itens', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('catalogo.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'fabricante' => 'required',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria' => 'nullable',
            'tipo_papel' => 'nullable',
            'tipo_impressora' => 'nullable', // Sincronizado com o Blade
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

        // Corrigido para plural para bater com o web.php
        return redirect()->route('catalogos.index')->with('success', 'Item cadastrado!');
    }
}