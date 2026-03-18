<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUIA ADI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-50 flex">

    <aside class="w-64 bg-slate-900 min-h-screen text-slate-300 flex flex-col shadow-xl">
        <div class="p-6 text-white font-bold text-2xl border-b border-slate-800 flex items-center gap-2">
            <i class="ph ph-package text-red-400"></i> Guia ADI
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('guia-adi.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('guia-adi.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-printer text-xl"></i> Guia Impressoras
            </a>

            <a href="{{ route('clientes.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('clientes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-building-office text-xl"></i> Clientes
            </a>

            <a href="{{ route('estoques.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('estoques.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-package text-xl"></i> Estoques
            </a>
            <a href="{{ route('tecnicos.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('tecnicos.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-users text-xl"></i> Tecnicos
            </a>

            <a href="{{ route('equipamentos.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('equipamentos.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-desktop text-xl"></i> Equipamentos
            </a>

            <a href="{{ route('movimentacoes.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('movimentacoes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                <i class="ph ph-arrows-clockwise text-xl"></i> Movimentações
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <span class="text-gray-500 font-medium">@yield('subtitle', 'Visão Geral')</span>
            <div class="flex items-center gap-4 text-gray-400">
                <i class="ph ph-bell text-2xl hover:text-blue-500 cursor-pointer"></i>
                <i class="ph ph-gear text-2xl hover:text-blue-500 cursor-pointer"></i>
            </div>
        </header>

        <main class="p-8">
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                <i class="ph ph-check-circle text-xl"></i> {{ session('success') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>

{--Script de alerta--}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>