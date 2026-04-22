<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::orderBy('modelo')->get();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'placa'  => 'required|unique:veiculos|max:10',
            'modelo' => 'required|string|max:255',
            'marca'  => 'nullable|string'
        ]);

        Veiculo::create($validated);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado!');
    }

    public function edit(Veiculo $veiculo)
    {
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        $request->validate([
            'placa'  => 'required|max:10|unique:veiculos,placa,' . $veiculo->id,
            'modelo' => 'required|string|max:255',
        ]);

        $veiculo->update($request->all());

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado!');
    }

    public function destroy(Veiculo $veiculo)
    {
        $veiculo->delete();
        return redirect()->route('veiculos.index')->with('success', 'Veículo removido!');
    }
}
