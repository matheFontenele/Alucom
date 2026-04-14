<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Confia nos proxies do Render para que o HTTPS seja identificado corretamente
        $middleware->trustProxies(at: '*');

        // 2. Define os redirecionamentos padrão do sistema
        $middleware->redirectTo(
            guests: '/login',      // Para onde vai quem NÃO está logado
            users: '/guia-adi'    // Para onde vai quem JÁ está logado e tenta ir pro login
        );

        // 3. REGISTRO DO ALIAS (CORREÇÃO DO ERRO 'funcao')
        // Isso vincula o nome 'funcao' usado nas rotas à classe do Middleware
        $middleware->alias([
            'funcao' => \App\Http\Middleware\CheckFuncao::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();