<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AS - Sistema - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        aside::-webkit-scrollbar {
            width: 4px;
        }

        aside::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .main-content {
            min-height: calc(100vh - 64px);
        }
    </style>
</head>

<body class="bg-gray-50 flex font-sans text-slate-900 overflow-x-hidden h-screen">

    {{-- Menu Lateral --}}
    <aside x-data="{ openMenu: '' }" class="w-64 flex-shrink-0 bg-slate-900 h-screen text-slate-300 flex flex-col shadow-xl sticky top-0 z-20 transition-all duration-300">

        <div class="p-6 text-white font-bold text-2xl border-b border-slate-800 flex items-center gap-2">
            <i class="ph ph-package text-red-500"></i> AS - Sistema
        </div>

        {{-- Área de Scroll do Menu --}}
        <div class="flex-1 py-4 space-y-2 overflow-y-auto">

            {{-- Dashboard --}}
            <div class="px-4">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition group {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-900/40' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="ph ph-house text-xl {{ request()->routeIs('dashboard') ? 'text-white' : 'text-red-500' }}"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">Dashboard</span>
                </a>
            </div>

            {{-- Requisições --}}
            <div class="px-4">
                <a href="{{ route('requisicoes.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition group {{ request()->routeIs('requisicoes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/40' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="ph ph-clipboard-text text-xl {{ request()->routeIs('requisicoes.*') ? 'text-white' : 'text-red-500' }}"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">Requisições</span>
                </a>
            </div>

            {{-- Guia de Impressoras --}}
            <div class="px-4">
                <a href="{{ route('guia-adi.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition group {{ request()->routeIs('guia-adi.*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/40' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i class="ph ph-printer text-xl {{ request()->routeIs('guia-adi.*') ? 'text-white' : 'text-red-500' }}"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">Guia Impressoras</span>
                </a>
            </div>

            <hr class="mx-6 border-slate-800 my-2">

        </div>

        {{-- Perfil (Fixo no rodapé do aside) --}}
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-3 p-2 bg-slate-800/40 rounded-xl border border-slate-700/50">
                <div class="w-10 h-10 rounded-lg bg-red-600 flex items-center justify-center text-white font-black shadow-lg uppercase">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Usuário' }}</span>
                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">{{ Auth::user()->funcao ?? 'Acesso' }}</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- Lado Direito (Conteúdo Principal) --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm flex-shrink-0">
            <div class="flex items-center gap-2">
                <i class="ph ph-caret-right text-slate-400"></i>
                <span class="text-slate-600 font-semibold tracking-tight uppercase text-sm">@yield('subtitle', 'Visão Geral')</span>
            </div>

            {{-- Logout --}}
            <div class="flex items-center gap-5 text-slate-400">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button type="button"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="hover:text-red-600 transition-all duration-200 flex items-center group"
                    title="Sair do Sistema">
                    <i class="ph ph-sign-out text-2xl group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </header>

        <main class="p-8 main-content overflow-y-auto bg-gray-50 flex-1">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                <div class="bg-emerald-500 text-white p-4 rounded-2xl mb-6 shadow-lg flex items-center gap-3">
                    <i class="ph ph-check-circle text-2xl"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>