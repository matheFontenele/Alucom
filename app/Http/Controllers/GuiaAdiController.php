<?php

namespace App\Http\Controllers;

use App\Models\GuiaAdi; // Importação correta para não precisar usar \App\Models\ toda hora
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaAdiController extends Controller
{
    public function index(Request $request)
    {
        $query = GuiaAdi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca_modelo', 'like', "%{$search}%")
                    ->orWhere('fabricante', 'like', "%{$search}%")
                    ->orWhere('toner', 'like', "%{$search}%");
            });
        }

        $guias = $query->latest()->get();
        return view('guia.index', compact('guias'));
    }

    public function create()
    {
        return view('guia.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $data = $request->validate([
            'fabricante'   => 'required|string|max:255',
            'marca_modelo' => 'required|string|max:255',
            'toner'        => 'nullable|string',
            'rendimento'   => 'nullable|string',
            'obs'          => 'nullable|string',
            'funcoes'      => 'nullable|array',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Tratamento do Upload da Foto
        if ($request->hasFile('foto')) {
            // Salva na pasta 'public/equipamentos' e guarda o caminho no banco
            $data['foto'] = $request->file('foto')->store('equipamentos', 'public');
        }

        GuiaAdi::create($data);

        return redirect()->route('guia-adi.index')->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function show($id)
    {
        $guiaAdi = GuiaAdi::findOrFail($id);

        // Garante que funcoes seja um array para o @foreach não quebrar na view
        if (!$guiaAdi->funcoes) {
            $guiaAdi->funcoes = [];
        }

        return view('guia.show', compact('guiaAdi'));
    }

    //Metedo para editar
    public function edit($id)
    {
        $guiaAdi = GuiaAdi::findOrFail($id);
        return view('guia.edit', compact('guiaAdi'));
    }

    //Metodo para salvar alterações
    public function update(Request $request, $id)
    {
        $guiaAdi = GuiaAdi::findOrFail($id);
        $data = $request->validate([
            'fabricante'   => 'required|string',
            'marca_modelo' => 'required|string',
            'toner'        => 'required|string',
            'ppm'          => 'nullable|string',
            'rendimento'   => 'nullable|string',
            'voltagem'     => 'nullable|string',
            'duplex'       => 'nullable|string',
            'obs'          => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        if ($request->hasFile('foto')) {

            $path = $request->file('foto')->store('guias', 'public');
            $data['foto'] = $path;
        }

        $guiaAdi->update($data);

        return redirect()->route('guia-adi.show', $guiaAdi->id)
            ->with('success', 'Equipamento atualizado com sucesso!');
    }
}
