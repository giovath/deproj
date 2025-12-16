<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Game Arena') }}</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Tailwind CDN (temporário, sem build) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.getElementById('loginModal').classList.add('flex');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
            document.getElementById('loginModal').classList.remove('flex');
        }
    </script>

</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col font-display">

    <!-- HEADER -->
    <header class="border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-emerald-400">
                    {{ config('app.name', 'ClickPrêmio') }}
                </h1>
                <span class="text-xs text-slate-400">
                    disputas 1v1 · partidas rápidas · ranking
                </span>
            </div>

            <nav class="space-x-6 text-sm text-slate-400">
                <a href="#" class="hover:text-white transition">Ranking</a>
                <a href="#" class="hover:text-white transition">Como funciona</a>
            </nav>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1 flex items-center justify-center">
        <div class="max-w-5xl w-full px-6">

            <!-- LOBBY CARD -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-10 shadow-2xl">

                <h2 class="text-3xl font-bold text-center mb-3 tracking-tight">
                    Arena 1v1
                </h2>

                <p class="text-center text-slate-400 mb-12">
                    Entre na arena, enfrente um oponente e avance no ranking.
                </p>

                <!-- SLOTS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- SLOT JOGADOR 1 -->
                    <div
                        class="border border-slate-800 rounded-2xl p-8 text-center
                               cursor-pointer transition group
                               hover:border-emerald-400 hover:bg-emerald-400/5"
                        onclick="openLoginModal()">

                        <div class="text-xs uppercase tracking-widest text-slate-400 mb-3">
                            Jogador 1
                        </div>

                        <div class="text-lg font-semibold text-slate-300 group-hover:text-white">
                            Vaga disponível
                        </div>

                        <div class="mt-2 text-xs text-slate-500">
                            Clique para entrar
                        </div>
                    </div>

                    <!-- SLOT JOGADOR 2 -->
                    <div
                        class="border border-slate-800 rounded-2xl p-8 text-center
                               cursor-pointer transition group
                               hover:border-amber-400 hover:bg-amber-400/5"
                        onclick="openLoginModal()">

                        <div class="text-xs uppercase tracking-widest text-slate-400 mb-3">
                            Jogador 2
                        </div>

                        <div class="text-lg font-semibold text-slate-300 group-hover:text-white">
                            Vaga disponível
                        </div>

                        <div class="mt-2 text-xs text-slate-500">
                            Clique para entrar
                        </div>
                    </div>

                </div>
            </div>

            <!-- RANKING PREVIEW -->
            <div class="mt-12 bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    🏆 Ranking em destaque
                </h3>

                <ul class="space-y-3 text-sm text-slate-400">
                    <li>#1 — Jogador Alpha</li>
                    <li>#2 — Jogador Beta</li>
                    <li>#3 — Jogador Gamma</li>
                </ul>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-slate-800 text-center text-xs text-slate-500 py-5">
        © {{ date('Y') }} {{ config('app.name', 'ClickPrêmio') }} ·
        <a href="{{ route('terms') }}" class="hover:text-white">Termos</a> ·
        <a href="{{ route('privacy') }}" class="hover:text-white">Privacidade</a>
    </footer>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 max-w-md w-full text-center shadow-2xl">
            <h3 class="text-2xl font-bold mb-2">
                Entrar na Arena
            </h3>

            <p class="text-sm text-slate-400 mb-8">
                Faça login com sua conta TikTok para ocupar um slot e iniciar a partida.
            </p>

            <a href="{{ route('auth.tiktok.redirect') }}"
                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                       bg-emerald-500 hover:bg-emerald-400 transition
                       text-slate-900 font-semibold">
                🎵 Entrar com TikTok
            </a>

            <button onclick="closeLoginModal()" class="mt-5 text-xs text-slate-500 hover:text-white">
                Cancelar
            </button>
        </div>
    </div>

</body>


</html>
