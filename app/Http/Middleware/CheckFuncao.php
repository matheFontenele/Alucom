<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFuncao
{
    /**
     * Manipula uma requisição de entrada.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$funcoes (Lista de funções permitidas passadas na rota)
     */
    public function handle(Request $request, Closure $next, ...$funcoes): Response
    {
        // 1. Verifica se o usuário está logado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Recupera a função do usuário logado
        $userFuncao = Auth::user()->funcao;

        // 3. Verifica se a função dele está entre as permitidas (Direção, Gerência, etc)
        if (!in_array($userFuncao, $funcoes)) {
            // Se não tiver permissão, aborta com erro 403 (Proibido)
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}