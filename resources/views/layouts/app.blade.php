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
        select option:disabled { color: #cbd5e1; background-color: #f8fafc; }
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-50 flex font-sans text-slate-900">

    <aside x-data="{ 
        openMenu: '{{ request()->routeIs('guia-adi.*', 'clientes.*', 'catalogos.*', 'tecnicos.*', 'estoques.*') ? 'operacao' : (request()->routeIs('requisicoes.*', 'rotas.*', 'movimentacoes.*', 'veiculos.*') ? 'logistica' : (request()->routeIs('usuarios.*') ? 'gerenciamento' : '')) }}' 
    }" class="w-64 bg-slate-900 min-h-screen text-slate-300 flex flex-col shadow-xl sticky top-0 h-screen overflow-y-auto">
        
        <div class="p-6 text-white font-bold text-2xl border-b border-slate-800 flex items-center gap-2">
            <i class="ph ph-package text-red-500"></i> Guia ADI
        </div>

        <div class="flex-1 py-4 space-y-2">
            
            <div class="px-4">
                <button @click="openMenu = (openMenu === 'operacao' ? '' : 'operacao')" 
                    class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-slate-800 transition group"
                    :class="openMenu === 'operacao' ? 'text-white' : ''">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-cpu text-xl text-red-500"></i>
                        <span class="text-sm font-bold uppercase tracking-wider">Operações</span>
                    </div>
                    <i class="ph ph-caret-down transition-transform duration-300" :class="openMenu === 'operacao' ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openMenu === 'operacao'" x-cloak x-collapse class="mt-1 space-y-1 ml-4 border-l border-slate-700 pl-2">
                    <x-nav-link href="{{ route('guia-adi.index') }}" active="{{ request()->routeIs('guia-adi.*') }}" icon="ph-printer" label="Guia Impressoras" />
                    <x-nav-link href="{{ route('clientes.index') }}" active="{{ request()->routeIs('clientes.*') }}" icon="ph-building-office" label="Clientes" />
                    <x-nav-link href="{{ route('catalogos.index') }}" active="{{ request()->routeIs('catalogos.*') }}" icon="ph-book-open-text" label="Catálogo" />
                    <x-nav-link href="{{ route('tecnicos.index') }}" active="{{ request()->routeIs('tecnicos.*') }}" icon="ph-wrench" label="Técnicos" />
                    <x-nav-link href="{{ route('estoques.index') }}" active="{{ request()->routeIs('estoques.*') }}" icon="ph-archive" label="Estoques" />
                </div>
            </div>

            <div class="px-4">
                <button @click="openMenu = (openMenu === 'logistica' ? '' : 'logistica')" 
                    class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-slate-800 transition group"
                    :class="openMenu === 'logistica' ? 'text-white' : ''">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-truck text-xl text-blue-500"></i>
                        <span class="text-sm font-bold uppercase tracking-wider">Logística</span>
                    </div>
                    <i class="ph ph-caret-down transition-transform duration-300" :class="openMenu === 'logistica' ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openMenu === 'logistica'" x-cloak x-collapse class="mt-1 space-y-1 ml-4 border-l border-slate-700 pl-2">
                    <x-nav-link href="{{ route('requisicoes.index') }}" active="{{ request()->routeIs('requisicoes.*') }}" icon="ph-clipboard-text" label="Requisições" />
                    <x-nav-link href="{{ route('rotas.index') }}" active="{{ request()->routeIs('rotas.*') }}" icon="ph-map-trifold" label="Rotas" />
                    <x-nav-link href="{{ route('movimentacoes.index') }}" active="{{ request()->routeIs('movimentacoes.*') }}" icon="ph-arrows-clockwise" label="Movimentações" />
                    <x-nav-link href="{{ route('veiculos.index') }}" active="{{ request()->routeIs('veiculos.*') }}" icon="ph-car" label="Veículos" />
                </div>
            </div>

            <div class="px-4">
                <button @click="openMenu = (openMenu === 'gerenciamento' ? '' : 'gerenciamento')" 
                    class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-slate-800 transition group"
                    :class="openMenu === 'gerenciamento' ? 'text-white' : ''">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-gear text-xl text-emerald-500"></i>
                        <span class="text-sm font-bold uppercase tracking-wider">Gerenciamento</span>
                    </div>
                    <i class="ph ph-caret-down transition-transform duration-300" :class="openMenu === 'gerenciamento' ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openMenu === 'gerenciamento'" x-cloak x-collapse class="mt-1 space-y-1 ml-4 border-l border-slate-700 pl-2">
                    <x-nav-link href="{{ route('usuarios.index') }}" active="{{ request()->routeIs('usuarios.*') }}" icon="ph-users-three" label="Usuários" />
                </div>
            </div>

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
                <form method="POST" action="{{ route('logout') }}">
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