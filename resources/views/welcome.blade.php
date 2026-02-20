@php
    use App\Models\GameMatch;

    $match = null;
    $slot1User = null;
    $slot2User = null;

    if (session()->has('match_id')) {
        $match = GameMatch::with(['slot1User', 'slot2User'])
            ->where('id', session('match_id'))
            ->whereIn('status', ['waiting', 'ready', 'playing'])
            ->first();

        // Se o match não existe mais, limpa a sessão
        if (!$match) {
            session()->forget('match_id');
        }
    }

    $slot1User = $match?->slot1User;
    $slot2User = $match?->slot2User;
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'premio.click') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Q2GDTV3FK2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-Q2GDTV3FK2');
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16791427286"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-16791427286');
    </script>

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

    <style>
        @keyframes pulseSoft {
            0% {
                transform: scale(1);
                filter: brightness(1);
            }

            50% {
                transform: scale(1.04);
                filter: brightness(1.1);
            }

            100% {
                transform: scale(1);
                filter: brightness(1);
            }
        }

        .chest-pulse {
            animation: pulseSoft 1.8s ease-in-out infinite;
        }

        .chest-opened {
            opacity: 0.5;
            filter: grayscale(0.3);
            animation: none;
            cursor: default;
        }
    </style>

</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col">

    <!-- HEADER -->
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex items-center justify-center px-4">
        <div class="w-full max-w-sm flex flex-col gap-8">

            <!-- ARENA SLOTS -->
            <div class="flex flex-col gap-5">
                <div class="grid grid-cols-2 gap-4">

                    <!-- SLOT 1 -->
                    <div data-slot="1" @if (!$slot1User) onclick="openLoginModal()" @endif
                        class="aspect-square rounded-2xl border
                        {{ $slot1User ? 'border-amber-400' : 'border-zinc-800 hover:border-amber-400 cursor-pointer' }}
                        bg-zinc-900 flex flex-col items-center justify-center transition">
                        @if ($slot1User)
                            <img src="{{ $slot1User->avatar }}" alt="Avatar"
                                class="w-20 h-20 rounded-full object-cover">
                            <span class="text-xs text-zinc-300">{{ $slot1User->name }}</span>
                        @else
                            <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                            <span class="text-xs text-zinc-400">slot livre</span>
                        @endif
                    </div>

                    <!-- SLOT 2 -->
                    <div data-slot="2" @if (!$slot2User) onclick="openLoginModal()" @endif
                        class="aspect-square rounded-2xl border
                        {{ $slot2User ? 'border-amber-400' : 'border-zinc-800 hover:border-amber-400 cursor-pointer' }}
                        bg-zinc-900 flex flex-col items-center justify-center transition">
                        @if ($slot2User)
                            <img src="{{ $slot2User->avatar }}" alt="Avatar"
                                class="w-20 h-20 rounded-full object-cover">
                            <span class="text-xs text-zinc-300">{{ $slot2User->name }}</span>
                        @else
                            <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                            <span class="text-xs text-zinc-400">slot livre</span>
                        @endif
                    </div>
                </div>

                <!-- BOTÃO CONVIDAR AMIGO -->
                @if ($match && auth()->check())
                    @if ($match->slot_1_user_id === auth()->id() && !$match->slot_2_user_id)
                        <div class="flex justify-center mt-2">
                            <button onclick="copyInviteLink()"
                                class="text-xs px-4 py-2 rounded-xl
                                       border border-zinc-700
                                       hover:border-amber-400 hover:text-amber-400
                                       transition text-zinc-300">
                                Convidar amigo
                            </button>
                        </div>
                    @endif
                @endif


                <!-- STATUS -->
                <div id="matchStatus" class="flex items-center justify-center gap-2 text-xs mt-3">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    aguardando jogadores
                </div>


                @if (auth()->check())
                    <button id="startGameBtn" type="button" onclick="startGame(matchId)"
                        class="w-full px-4 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 hidden">
                        Iniciar jogo
                    </button>
                @endif



            </div>

            <div class="space-y-2">

                <!-- BAÚ DIÁRIO -->
                <div class="flex flex-col items-center">


                    <button id="chestBtn" onclick="openChest()"
                        class="p-0 bg-transparent hover:opacity-80 transition cursor-pointer">
                        <img id="chestImg" src="{{ asset('images/chest-closed.png') }}" alt="Baú"
                            class="w-52 h-52 chest-pulse">
                    </button>

                    <span class="text-xs text-amber-400 mb-1">
                        recompensas diárias
                    </span>

                </div>



                <a href="https://www.tiktok.com/d/1/ZS9eFHx7HjL58-r1TEo/" target="_blank" rel="noopener noreferrer"
                    class="flex items-center gap-3 bg-zinc-900 border border-zinc-800 hover:border-amber-400 rounded-xl px-3 py-2 hover:border-amber-400 transition">

                    <div class="w-8 h-8 rounded-full bg-amber-400/20 flex items-center justify-center text-xs">
                        🎁
                    </div>

                    <div class="flex flex-col leading-tight">
                        <span class="text-xs text-zinc-100 font-medium">
                            Recompensas TikTok
                        </span>
                        <span class="text-[10px] text-zinc-400">
                            Eventos oficiais • Ganhe prêmios reais
                        </span>
                    </div>
                </a>

                <a href="https://clickpremio.online/?src=hub" target="_blank" rel="noopener noreferrer"
                    class="flex items-center gap-3 bg-zinc-900/60 border border-zinc-800
          hover:border-zinc-600 rounded-xl px-3 py-2 transition">

                    <div class="w-8 h-8 rounded-full bg-zinc-700 flex items-center justify-center text-xs">
                        🏆
                    </div>

                    <div class="flex flex-col leading-tight">
                        <span class="text-xs text-zinc-300 font-medium">
                            Sorteios mensais
                        </span>
                        <span class="text-[10px] text-zinc-500">
                            Participe já • Cadastro gratuito
                        </span>
                    </div>
                </a>

            </div>


        </div>
    </main>

    <!-- FOOTER -->
    @include('partials.footer')

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-sm w-full mx-4 text-center shadow-xl">

            @if ($slot1User)
                <p class="text-xs text-zinc-400 mb-4">
                    {{ $slot1User->name }} te convidou para jogar
                </p>
            @endif

            <h3 class="text-lg font-semibold text-zinc-100 mb-1">Entrar na arena</h3>
            <p class="text-xs text-zinc-400 mb-6">Entre com sua conta TikTok para ocupar um slot.</p>

            <a href="{{ route('auth.tiktok.redirect') }}"
                class="w-full inline-flex items-center justify-center gap-3
                      px-4 py-3 rounded-xl bg-amber-400 hover:bg-amber-300
                      transition text-zinc-900 font-semibold text-sm">
                <img src="/images/TikTok.webp" alt="TikTok" class="w-5 h-5">
                Entrar com TikTok
            </a>

            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-zinc-800"></div>
                <span class="text-[10px] uppercase tracking-widest text-zinc-500">ou</span>
                <div class="flex-1 h-px bg-zinc-800"></div>
            </div>

            <p class="text-[11px] text-zinc-500 mb-3 leading-relaxed">
                Ainda não tem TikTok? Crie sua conta pelo convite e participe das recompensas.
            </p>

            <a href="https://www.tiktok.com/d/1/ZS9eRL6aaLfCG-0Sp5p/" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center
                      px-4 py-2 rounded-xl border border-zinc-700
                      hover:border-amber-400 hover:text-amber-400
                      transition text-xs font-medium text-zinc-300">
                Criar conta no TikTok
            </a>

            <button onclick="closeLoginModal()" class="mt-5 text-xs text-zinc-500 hover:text-zinc-300 transition">
                cancelar
            </button>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function openChest() {
            const img = document.getElementById('chestImg');

            img.src = "{{ asset('images/chest-open.png') }}";
            img.classList.remove('chest-pulse');
            img.classList.add('chest-opened');

            document.getElementById('chestBtn').disabled = true;
            window.location.href = 'https://www.tiktok.com/d/1/ZS9eFH4A5haed-yaCmT/';
        }

        let opponentLoaded = false;
        let startButtonShown = false;


        let matchId = {{ $match?->id ?? 'null' }};

        if (matchId) {
            setInterval(checkMatchStatus, 3000);
        }

        async function startGame(matchId) {
            const res = await fetch(`/arena/start/${matchId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });


            if (res.status === 409) {
                alert('Aguardando o outro jogador confirmar');
                return;
            }

            if (!res.ok) {
                alert('Erro ao iniciar o jogo');
                return;
            }

            const data = await res.json();
            window.location.href = data.redirect;
        }

        async function checkMatchStatus() {
            const response = await fetch(`/arena/status/${matchId}`, {
                headers: {
                    Accept: "application/json"
                }
            });
            if (!response.ok) return;

            const data = await response.json();
            if (!data.me) return;

            const mySlot = data.me.slot;
            const opponentSlot = mySlot === 1 ? 2 : 1;

            if (data.opponent && !opponentLoaded) {
                hydrateSlot(opponentSlot, data.opponent);
                opponentLoaded = true;
            }

            if (data.opponent && !startButtonShown) {
                showStartButton();
                startButtonShown = true;
            }

            updateMatchStatus(data);
        }


        function updateMatchStatus(data) {
            const el = document.getElementById('matchStatus');
            if (!el) return;

            if (data.opponent) {
                el.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            pronto para jogar
        `;
            } else {
                el.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            aguardando jogadores
        `;
            }
        }


        function showStartButton() {
            const btn = document.getElementById('startGameBtn');
            if (btn) btn.classList.remove('hidden');
        }



        function hydrateSlot(slotNumber, player) {
            if (!player || !player.id || !player.name || !player.avatar) return;

            const slot = document.querySelector(`[data-slot="${slotNumber}"]`);
            if (!slot) return;

            slot.innerHTML = `
        <img src="${player.avatar}" class="w-20 h-20 rounded-full object-cover">
        <span class="text-xs text-zinc-300">${player.name}</span>
    `;

            slot.classList.remove('border-zinc-800');
            slot.classList.add('border-amber-400');
            slot.onclick = null;
        }


        @if ($match)
            function copyInviteLink() {
                if (!matchId) return;
                const link = "{{ url('/invite') }}/" + matchId;
                navigator.clipboard.writeText(link);
                alert('Link de convite copiado!');
            }
        @endif
    </script>

    @if (session('invited_match_id') && !auth()->check())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                openLoginModal();
            });
        </script>
    @endif


</body>

</html>
