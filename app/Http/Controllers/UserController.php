<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Lista todos os usuários.
     */
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        $funcoes = $this->getFuncoes();
        return view('usuarios.create', compact('funcoes'));
    }

    /**
     * Salva um novo usuário no banco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'funcao'   => 'required|string'
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

    /**
     * Exibe os dados de um usuário específico (Opcional).
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(User $usuario)
    {
        $funcoes = $this->getFuncoes();
        return view('usuarios.edit', compact('usuario', 'funcoes'));
    }

    /**
     * Atualiza os dados do usuário.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'funcao' => 'required'
        ]);

        $data = $request->only(['name', 'email', 'funcao']);

        // Só atualiza a senha se o campo for preenchido
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'confirmed|min:8'
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove o usuário do sistema.
     */
    public function destroy(User $usuario)
    {
        // Evita que o usuário logado exclua a si mesmo
        if (auth()->id() === $usuario->id) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário removido!');
    }

    /**
     * Centraliza a lista de funções para evitar repetição.
     */
    private function getFuncoes()
    {
        return [
            'Estoque', 'Técnico', 'Motorista', 
            'Administrativo', 'Logística', 'Financeiro', 
            'Gerência', 'Direção'
        ];
    }
}