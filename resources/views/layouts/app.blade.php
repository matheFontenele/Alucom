<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUIA ADI - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        select option:disabled {
            color: #cbd5e1;
            background-color: #f8fafc;
        }
        /* Custom scrollbar para o menu lateral */
        aside::-webkit-scrollbar {
            width: 4px;
        }
        aside::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-50 flex font-sans text-slate-900">

    <aside class="w-64 bg-slate-900 min-h-screen text-slate-300 flex flex-col shadow-xl sticky top-0 h-screen overflow-y-auto">
        <div class="p-6 text-white font-bold text-2xl border-b border-slate-800 flex items-center gap-2">
            <i class="ph ph-package text-red-500"></i> Guia ADI
        </div>

        <div class="flex-1 py-4">
            <div class="px-6 mb-2">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Operação</span>
            </div>
            <nav class="px-4 space-y-1 mb-6">
                <a href="{{ route('guia-adi.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('guia-adi.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-printer text-xl"></i> Guia Impressoras
                </a>

                <a href="{{ route('requisicoes.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('requisicoes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-clipboard-text text-xl"></i> Requisições
                </a>

                <a href="{{ route('rotas.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('rotas.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-truck text-xl"></i> Rotas
                </a>

                <a href="{{ route('movimentacoes.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('movimentacoes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-arrows-clockwise text-xl"></i> Movimentações
                </a>
            </nav>

            <div class="px-6 mb-2">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Gerenciamento</span>
            </div>
            <nav class="px-4 space-y-1">
                <a href="{{ route('usuarios.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('usuarios.*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-users-three text-xl"></i> Usuários
                </a>

                <a href="{{ route('catalogos.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('catalogos.*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-book-open-text text-xl"></i> Catálogo
                </a>

                <a href="{{ route('veiculos.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('veiculos.*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-car text-xl"></i> Veículos
                </a>

                <a href="{{ route('clientes.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('clientes.*') ? 'hover:bg-slate-800 hover:text-white' : '' }}">
                    <i class="ph ph-building-office text-xl"></i> Clientes
                </a>

                <a href="{{ route('estoques.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('estoques.*') ? 'hover:bg-slate-800 hover:text-white' : '' }}">
                    <i class="ph ph-archive text-xl"></i> Estoques
                </a>

                <a href="{{ route('equipamentos.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('equipamentos.*') ? 'hover:bg-slate-800 hover:text-white' : '' }}">
                    <i class="ph ph-desktop text-xl"></i> Itens Físicos
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-3 p-2 bg-slate-800/40 rounded-xl">
                <div class="w-10 h-10 rounded-lg bg-red-600 flex items-center justify-center text-white font-black shadow-lg">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Usuário' }}</span>
                    <span class="text-[10px] text-slate-500 uppercase font-bold">{{ Auth::user()->funcao ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <i class="ph ph-caret-right text-slate-400"></i>
                <span class="text-slate-600 font-semibold tracking-tight uppercase text-sm">@yield('subtitle', 'Visão Geral')</span>
            </div>
            
            <div class="flex items-center gap-5 text-slate-400">
                <div class="relative cursor-pointer hover:text-red-500 transition">
                    <i class="ph ph-bell text-2xl"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-[10px] flex items-center justify-center rounded-full border-2 border-white">3</span>
                </div>
                <i class="ph ph-gear text-2xl hover:text-slate-600 cursor-pointer transition"></i>
                <div class="h-8 w-[1px] bg-slate-200"></div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="hover:text-red-600 transition">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="p-8">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
                class="mb-6 flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="ph ph-check-circle text-2xl"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false"><i class="ph ph-x text-xl"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
                class="mb-6 flex items-center justify-between bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="ph ph-warning-circle text-2xl"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false"><i class="ph ph-x text-xl"></i></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>