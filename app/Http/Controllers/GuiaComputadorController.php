<?php

namespace App\Http\Controllers;

use App\Models\GuiaComputador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaComputadorController extends Controller
{
    public function index(Request $request)
    {
        $query = GuiaComputador::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca_modelo', 'like', "%{$search}%")
                    ->orWhere('fabricante', 'like', "%{$search}%")
                    ->orWhere('processador', 'like', "%{$search}%")
                    ->orWhere('geracao', 'like', "%{$search}%");
            });
        }

        $guias = $query->latest()->get();
        return view('guia-computadores.index', compact('guias'));
    }

    public function create()
    {
        return view('guia-computadores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fabricante'   => 'required|string|max:255',
            'marca_modelo' => 'required|string|max:255',
            'processador'  => 'required|string',
            'memoria'      => 'required|string',
            'armazenamento' => 'required|string',
            'geracao'      => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('equipamentos/computadores', 'public');
        }

        GuiaComputador::create($data);

        return redirect()->route('guia-computadores.index')->with('success', 'Computador cadastrado!');
    }

    public function show($id)
    {
        $guia = GuiaComputador::findOrFail($id);
        return view('guia-computadores.show', compact('guia'));
    }

    public function edit($id)
    {
        $guia = GuiaComputador::findOrFail($id);
        return view('guia-computadores.edit', compact('guia'));
    }

    public function update(Request $request, $id)
    {
        $guia = GuiaComputador::findOrFail($id);
        $data = $request->validate([
            'fabricante'   => 'required|string',
            'marca_modelo' => 'required|string',
            'processador'  => 'required|string',
            'memoria'      => 'required|string',
            'armazenamento' => 'required|string',
            'geracao'      => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guia->foto) Storage::disk('public')->delete($guia->foto);
            $data['foto'] = $request->file('foto')->store('equipamentos/computadores', 'public');
        }

        $guia->update($data);

        return redirect()->route('guia-computadores.show', $guia->id)->with('success', 'Computador atualizado!');
    }
}
