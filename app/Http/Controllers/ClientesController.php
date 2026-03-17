<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{

    //Função principal
    public function index(Request $request)
    {
        $query = Clientes::query()->whereNull('parent_id');
        // Busca por Nome ou CNPJ
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', "%{$request->search}%")
                    ->orWhere('cnpj', 'like', "%{$request->search}%");
            });
        }

        // Filtro por Estado (Localização)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por Contrato
        if ($request->filled('contrato')) {
            $query->where('contrato', $request->contrato);
        }

        $clientes = $query->orderBy('nome')->get();
        return view('clientes.index', compact('clientes'));
    }

    //Função para mostrar os clientes 
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'      => 'required|string|max:255',
            'cnpj'      => 'required|string',
            'estado'    => 'required|string|max:2',
            'cidade'    => 'required|string|max:255',
            'endereco'  => 'required|string|max:255',
            'contrato'  => 'required|in:Alucom,Moreia,ZapLok',
            'sla'       => 'nullable|array',
            'parent_id' => 'nullable|exists:clientes,id',
            'tipo'      => 'required|in:ministerio,unidade',
        ]);

        // Força o parent_id como null se for ministério, por segurança
        if ($data['tipo'] === 'ministerio') {
            $data['parent_id'] = null;
        }

        Clientes::create($data);

        // Se for unidade, volta para o ministério pai, se não, volta para a lista
        if ($request->filled('parent_id')) {
            return redirect()->route('clientes.show', $request->parent_id)->with('success', 'Unidade cadastrada!');
        }

        return redirect()->route('clientes.index')->with('success', 'Ministério cadastrado!');
    }

    //Metedo para criar novo cliente
    public function create()
    {
        $ministerios = Clientes::whereNull('parent_id')->get();
        return view('clientes.create', compact('ministerios'));
    }

    // Adicione o método edit
    public function edit($id)
    {
        $cliente = Clientes::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    //Metodo para atualizar dados de clientes (editar)
    public function update(Request $request, $id)
    {
        $cliente = Clientes::findOrFail($id);

        $data = $request->validate([
            'nome'     => 'required|string|max:255',
            'cnpj'     => 'required|string|unique:clientes,cnpj,' . $id,
            'estado'   => 'required|string|max:2',
            'cidade'   => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'contrato' => 'required|in:Alucom,Moreia,ZapLok',
            'sla'      => 'nullable|array',
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function show(Request $request, $id)
    {
        // Busca o cliente principal
        $cliente = Clientes::findOrFail($id);

        // Inicia a query das unidades vinculadas (relacionamento)
        $queryUnidades = $cliente->unidades();

        // Aplica o filtro de busca de região/nome se existir
        if ($request->filled('search_unidade')) {
            $search = $request->search_unidade;
            $queryUnidades->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('cidade', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%");
            });
        }

        // Pega os resultados
        $unidades = $queryUnidades->get();

        // Envia tanto o $cliente quanto as $unidades para a view
        return view('clientes.show', compact('cliente', 'unidades'));
    }
}
