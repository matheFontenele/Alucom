<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Guia ADi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen font-sans">
    <div class="w-full max-w-md p-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-slate-900 p-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 rounded-2xl shadow-lg mb-4">
                    <i class="ph ph-package text-white text-3xl"></i>
                </div>
                <h1 class="text-white text-2xl font-black tracking-tight">GUIA ADi</h1>
                <p class="text-slate-400 text-sm mt-1 uppercase tracking-widest font-bold">Gestão de Inventário</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1 ml-1">E-mail Corporativo</label>
                    <div class="relative">
                        <i class="ph ph-envelope-simple absolute left-3 top-3 text-slate-400"></i>
                        <input type="email" name="email" required autofocus
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all"
                            placeholder="exemplo@adi.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1 ml-1">Senha</label>
                    <div class="relative">
                        <i class="ph ph-lock-simple absolute left-3 top-3 text-slate-400"></i>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 transition-all transform active:scale-[0.98]">
                    Acessar Sistema
                </button>
            </form>
        </div>
        <p class="text-center text-slate-400 text-xs mt-6 uppercase font-bold tracking-tighter">© {{ date('Y') }} Alucom - Versão 2.0</p>
    </div>
</body>
</html>