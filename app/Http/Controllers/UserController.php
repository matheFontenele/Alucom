<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        // Lista de funções que definimos anteriormente
        $funcoes = [
            'Estoque', 'Técnico', 'Motorista', 
            'Administrativo', 'Logística', 'Financeiro', 'Gerência'
        ];
        return view('usuarios.create', compact('funcoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'funcao'   => 'required'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'funcao'   => $request->funcao,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $usuario)
    {
        $funcoes = ['Estoque', 'Técnico', 'Motorista', 'Administrativo', 'Logística', 'Financeiro', 'Gerência'];
        return view('usuarios.edit', compact('usuario', 'funcoes'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $usuario->id,
            'funcao' => 'required'
        ]);

        $data = $request->only(['name', 'email', 'funcao']);

        // Só atualiza a senha se o campo for preenchido
        if ($request->filled('password')) {
            $request->validate(['password' => 'confirmed|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário removido!');
    }
}