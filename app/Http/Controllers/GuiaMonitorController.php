<?php

namespace App\Http\Controllers;

use App\Models\GuiaMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = GuiaMonitor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca_modelo', 'like', "%{$search}%")
                    ->orWhere('fabricante', 'like', "%{$search}%")
                    ->orWhere('polegadas', 'like', "%{$search}%");
            });
        }

        $guias = $query->latest()->get();
        return view('guia-monitores.index', compact('guias'));
    }

    public function create()
    {
        return view('guia-monitores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fabricante'   => 'required|string|max:255',
            'marca_modelo' => 'required|string|max:255',
            'polegadas'    => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('equipamentos/monitores', 'public');
        }

        GuiaMonitor::create($data);

        return redirect()->route('guia-monitores.index')->with('success', 'Monitor cadastrado com sucesso!');
    }

    public function show($id)
    {
        $guia = GuiaMonitor::findOrFail($id);
        return view('guia-monitores.show', compact('guia'));
    }

    public function edit($id)
    {
        $guia = GuiaMonitor::findOrFail($id);
        return view('guia-monitores.edit', compact('guia'));
    }

    public function update(Request $request, $id)
    {
        $guia = GuiaMonitor::findOrFail($id);
        $data = $request->validate([
            'fabricante'   => 'required|string',
            'marca_modelo' => 'required|string',
            'polegadas'    => 'required|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guia->foto) Storage::disk('public')->delete($guia->foto);
            $data['foto'] = $request->file('foto')->store('equipamentos/monitores', 'public');
        }

        $guia->update($data);

        return redirect()->route('guia-monitores.show', $guia->id)->with('success', 'Monitor atualizado!');
    }
}
