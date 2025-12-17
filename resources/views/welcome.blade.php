<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Arena') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CDN -->
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

<body class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">

    <!-- APP HEADER -->
    <header class="h-14 flex items-center justify-between px-4 border-b border-zinc-800">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-zinc-800"></div>
            <span class="text-sm font-medium text-zinc-300">
                {{ config('app.name', 'Arena') }}
            </span>
        </div>

        <div class="w-6 h-6 rounded-full bg-zinc-700"></div>
    </header>

    <!-- CONTENT -->
    <main class="flex-1 flex items-center justify-center px-4">

        <div class="w-full max-w-sm flex flex-col gap-8">

            <!-- ARENA -->
            <div class="flex flex-col gap-5">

                <!-- Slots -->
                <div class="grid grid-cols-2 gap-4">

                    <div onclick="openLoginModal()"
                        class="aspect-square rounded-2xl border border-zinc-800 bg-zinc-900
                               flex flex-col items-center justify-center
                               hover:border-amber-400 transition cursor-pointer">

                        <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                        <span class="text-xs text-zinc-400">slot livre</span>
                    </div>

                    <div onclick="openLoginModal()"
                        class="aspect-square rounded-2xl border border-zinc-800 bg-zinc-900
                               flex flex-col items-center justify-center
                               hover:border-amber-400 transition cursor-pointer">

                        <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                        <span class="text-xs text-zinc-400">slot livre</span>
                    </div>

                </div>

                <!-- Status neutro -->
                <div class="flex items-center justify-center gap-2 text-zinc-500 text-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    aguardando jogadores
                </div>

            </div>

            <!-- RANKING -->
            <div class="flex flex-col gap-3">

                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-widest text-zinc-500">
                        Ranking
                    </span>
                    <span class="text-xs text-zinc-600">
                        Top 3
                    </span>
                </div>

                <div class="space-y-2">

                    <div class="flex items-center gap-3 bg-zinc-900 border border-zinc-800 rounded-xl px-3 py-2">
                        <span class="text-xs text-zinc-500 w-4">#1</span>
                        <div class="w-8 h-8 rounded-full bg-zinc-700"></div>
                        <div class="flex-1 h-2 rounded bg-zinc-800"></div>
                    </div>

                    <div class="flex items-center gap-3 bg-zinc-900 border border-zinc-800 rounded-xl px-3 py-2">
                        <span class="text-xs text-zinc-500 w-4">#2</span>
                        <div class="w-8 h-8 rounded-full bg-zinc-700"></div>
                        <div class="flex-1 h-2 rounded bg-zinc-800"></div>
                    </div>

                    <div class="flex items-center gap-3 bg-zinc-900 border border-zinc-800 rounded-xl px-3 py-2">
                        <span class="text-xs text-zinc-500 w-4">#3</span>
                        <div class="w-8 h-8 rounded-full bg-zinc-700"></div>
                        <div class="flex-1 h-2 rounded bg-zinc-800"></div>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-zinc-800 text-center text-xs text-zinc-500 py-4">
        © {{ date('Y') }} {{ config('app.name', 'Arena') }}
    </footer>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

        <div
            class="bg-zinc-900 border border-zinc-800 rounded-2xl
               p-6 max-w-sm w-full mx-4
               text-center shadow-xl">

            <!-- Título -->
            <h3 class="text-lg font-semibold text-zinc-100 mb-1">
                Entrar na arena
            </h3>

            <!-- Texto -->
            <p class="text-xs text-zinc-400 mb-6">
                Entre com sua conta TikTok para ocupar um slot.
            </p>

            <!-- LOGIN TIKTOK -->
            <a href="{{ route('auth.tiktok.redirect') }}"
                class="w-full inline-flex items-center justify-center gap-3
                  px-4 py-3 rounded-xl
                  bg-amber-400 hover:bg-amber-300 transition
                  text-zinc-900 font-semibold text-sm">

                <img src="/images/TikTok.webp" alt="TikTok" class="w-5 h-5" />

                Entrar com TikTok
            </a>

            <!-- DIVISOR -->
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-zinc-800"></div>
                <span class="text-[10px] uppercase tracking-widest text-zinc-500">
                    ou
                </span>
                <div class="flex-1 h-px bg-zinc-800"></div>
            </div>

            <!-- REFERRAL -->
            <p class="text-[11px] text-zinc-500 mb-3 leading-relaxed">
                Ainda não tem TikTok?
                Crie sua conta pelo convite e participe das recompensas.
            </p>

            <a href="https://www.tiktok.com/d/J4878898528" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center
                  px-4 py-2 rounded-xl
                  border border-zinc-700
                  hover:border-amber-400 hover:text-amber-400
                  transition text-xs font-medium text-zinc-300">

                Criar conta no TikTok
            </a>

            <!-- CANCELAR -->
            <button onclick="closeLoginModal()" class="mt-5 text-xs text-zinc-500 hover:text-zinc-300 transition">
                cancelar
            </button>
        </div>
    </div>


</body>

</html>
