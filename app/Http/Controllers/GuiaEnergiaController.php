<?php

namespace App\Http\Controllers;

use App\Models\GuiaEnergia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaEnergiaController extends Controller
{
    public function index(Request $request)
    {
        $query = GuiaEnergia::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca_modelo', 'like', "%{$search}%")
                    ->orWhere('fabricante', 'like', "%{$search}%")
                    ->orWhere('potencia_va', 'like', "%{$search}%");
            });
        }

        $guias = $query->latest()->get();
        return view('guia-energia.index', compact('guias'));
    }

    public function create()
    {
        return view('guia-energia.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fabricante'   => 'required|string|max:255',
            'marca_modelo' => 'required|string|max:255',
            'potencia_va'  => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('equipamentos/energia', 'public');
        }

        GuiaEnergia::create($data);

        return redirect()->route('guia-energia.index')->with('success', 'Nobreak/Estabilizador cadastrado com sucesso!');
    }

    public function show($id)
    {
        $guia = GuiaEnergia::findOrFail($id);
        return view('guia-energia.show', compact('guia'));
    }

    public function edit($id)
    {
        $guia = GuiaEnergia::findOrFail($id);
        return view('guia-energia.edit', compact('guia'));
    }

    public function update(Request $request, $id)
    {
        $guia = GuiaEnergia::findOrFail($id);
        $data = $request->validate([
            'fabricante'   => 'required|string',
            'marca_modelo' => 'required|string',
            'potencia_va'  => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guia->foto) Storage::disk('public')->delete($guia->foto);
            $data['foto'] = $request->file('foto')->store('equipamentos/energia', 'public');
        }

        $guia->update($data);

        return redirect()->route('guia-energia.show', $guia->id)->with('success', 'Dados de energia atualizados!');
    }
}
