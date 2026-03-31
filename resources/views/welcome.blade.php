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

@php
    $gameName = null;

    if ($match?->game_code) {
        $games = collect(config('gamezop_games.sync_core'))->merge(config('gamezop_games.sync_casual'));

        $game = $games->firstWhere('code', $match->game_code);
        $gameName = $game['name'] ?? null;
    }
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

    <!-- Meta Pixel -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1201306888367899');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1201306888367899&ev=PageView&noscript=1" />
    </noscript>


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
            animation: pulseSoft 1.2s ease-in-out infinite;
            filter: drop-shadow(0 0 12px rgba(251, 191, 36, 0.6));
        }

        .chest-opened {
            opacity: 0.5;
            filter: grayscale(0.3);
            animation: none;
            cursor: default;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
                    <div data-slot="1" @if (!$slot1User && !auth()->check()) onclick="openLoginModal()" @endif
                        class="aspect-square rounded-2xl border
                        {{ $slot1User ? 'border-amber-400' : 'border-zinc-800 hover:border-amber-400 cursor-pointer' }}
                        bg-zinc-900 flex flex-col items-center justify-center transition">
                        @if ($slot1User)
                            <img src="{{ $slot1User->avatar }}" alt="Avatar"
                                class="w-20 h-20 rounded-full object-cover">
                            <span class="text-xs text-zinc-300">{{ $slot1User->name }}</span>
                        @else
                            <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                            <span class="text-xs text-amber-400 font-medium">Entrar para jogar</span>
                        @endif
                    </div>

                    <!-- SLOT 2 -->
                    <div data-slot="2" @if (!$slot2User && !auth()->check()) onclick="openLoginModal()" @endif
                        class="aspect-square rounded-2xl border
                        {{ $slot2User ? 'border-amber-400' : 'border-zinc-800 hover:border-amber-400 cursor-pointer' }}
                        bg-zinc-900 flex flex-col items-center justify-center transition">
                        @if ($slot2User)
                            <img src="{{ $slot2User->avatar }}" alt="Avatar"
                                class="w-20 h-20 rounded-full object-cover">
                            <span class="text-xs text-zinc-300">{{ $slot2User->name }}</span>
                        @else
                            <div class="w-16 h-16 rounded-full bg-zinc-700 mb-3"></div>
                            <span class="text-xs text-amber-400 font-medium">Entrar para jogar</span>
                        @endif
                    </div>
                </div>

                <!-- BOTÃO CONVIDAR AMIGO -->
                @if ($match && auth()->check())
                    @if ($match->slot_1_user_id === auth()->id() && !$match->slot_2_user_id)
                        <div id="inviteBtn" class="flex justify-center mt-2 gap-2">
                            <button onclick="copyInviteLink()"
                                class="text-xs px-4 py-2 rounded-xl
                       bg-amber-400 text-zinc-900 font-semibold
                       hover:bg-amber-300 hover:scale-105
                       transition-all duration-200">
                                Convidar amigo
                            </button>

                            <a href="https://joga.click/?src=solo" target="_blank"
                                class="text-xs px-4 py-2 rounded-xl
                       bg-zinc-800 text-zinc-100 font-semibold
                       hover:bg-zinc-700 hover:scale-105
                       transition-all duration-200">
                                Jogos individuais
                            </a>
                        </div>
                    @endif
                @endif


                <!-- STATUS -->
                <div id="matchStatus" class="flex items-center justify-center gap-2 text-xs mt-3">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Aguardando oponente
                </div>

                @if ($match && auth()->check())
                    <button onclick="leaveMatch()" class="text-[10px] text-zinc-500 hover:text-red-400 transition mt-2">
                        Sair da arena
                    </button>
                @endif

                @if (
                    $match &&
                        auth()->check() &&
                        !$match->game_code &&
                        $match->slot_1_user_id &&
                        $match->slot_2_user_id &&
                        in_array(auth()->id(), [$match->slot_1_user_id, $match->slot_2_user_id]))
                    <div id="gameChooser" class="mt-3">
                        <button onclick="openGameChooser()"
                            class="w-full px-4 py-3 rounded-xl
           bg-amber-400 text-zinc-900 font-semibold
           hover:bg-amber-300 hover:scale-105
           transition-all duration-200">
                            Escolher jogo
                        </button>
                    </div>
                @endif

                @if (auth()->check())
                    <button id="startGameBtn" type="button" onclick="startGame()"
                        class="w-full px-4 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 hidden">
                        Iniciar o jogo
                    </button>
                @endif



            </div>

            <div class="space-y-2">

                <!-- BAÚ DIÁRIO -->
                <div class="flex flex-col items-center">


                    <button id="chestBtn" onclick="openChest()"
                        class="p-0 bg-transparent hover:opacity-80 transition cursor-pointer">
                        <img id="chestImg" src="{{ asset('images/chest-closed.png') }}" alt="Baú"
                            class="w-56 h-56 chest-pulse transition-transform duration-300 hover:scale-105">
                    </button>

                    <div class="text-center mb-2">
                        <div class="text-xs text-amber-400 font-semibold">
                            🎁 Baú de recompensa diária
                        </div>
                        <div class="text-[10px] text-zinc-400">
                            Disponível agora
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-4">

                <a href="/oportunidades"
                    class="block w-full text-center py-4 rounded-xl
    bg-emerald-400 text-zinc-900 font-semibold
    hover:scale-[1.02] transition">

                    💡 Ver oportunidades disponíveis hoje

                    <div class="text-xs opacity-70 font-normal mt-1">
                        Apps, tarefas e formas de usar o celular
                    </div>

                </a>

            </div>

            <div class="mt-3">

                <a href="/recompensas"
                    class="block w-full text-center py-4 rounded-xl
    bg-amber-400 text-zinc-900 font-semibold
    hover:scale-[1.02] transition">

                    🚀 Ganhar mais recompensas agora

                    <div class="text-xs opacity-70 font-normal mt-1">
                        Várias maneiras de ganhar online
                    </div>

                </a>

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

            <h3 class="text-lg font-semibold text-zinc-100 mb-2">
                Entrar na arena
            </h3>

            <p class="text-xs text-zinc-400 mb-6 leading-relaxed">
                Conecte sua conta TikTok<br>
                para jogar e participar das
                <span class="text-amber-400 font-medium">recompensas</span>.
            </p>

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

            <div class="mb-4">
                <p class="text-xs text-zinc-300 font-medium mb-1">
                    Novo no TikTok?
                </p>
                <p class="text-[11px] text-zinc-400 leading-relaxed">
                    Crie sua conta pelo convite e receba
                    <span class="text-amber-400">recompensas exclusivas</span>.
                </p>
            </div>

            <a href="https://www.tiktok.com/d/1/ZS9RG7dPNvBXD-bI3AM/" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center
                   px-4 py-2 rounded-xl border border-zinc-700
                   hover:border-amber-400 hover:text-amber-400
                   transition text-xs font-medium text-zinc-300">
                Criar conta no TikTok
            </a>

            <button onclick="closeLoginModal()" class="mt-5 text-xs text-zinc-600 hover:text-zinc-300 transition">
                Cancelar
            </button>

        </div>
    </div>

    <!-- GAMES MODAL -->
    <div id="genericModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
        <div
            class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5
           max-w-sm w-full mx-4 shadow-xl text-zinc-100
           max-h-[80vh] overflow-y-auto">
            <div id="genericModalContent"></div>

            <button onclick="closeModal()"
                class="mt-4 w-full text-xs text-zinc-400
                       hover:text-zinc-200 transition">
                fechar
            </button>
        </div>
    </div>


    <!-- SCRIPTS -->
    <script>
        function openChest() {

            if (chestOpened) return;

            const img = document.getElementById('chestImg');

            img.src = "{{ asset('images/chest-open.png') }}";
            img.classList.remove('chest-pulse');
            img.classList.add('chest-opened');

            document.getElementById('chestBtn').disabled = true;

            // 👉 SALVA ESTADO
            chestOpened = true;
            localStorage.setItem('chest_opened', todayKey);

            setTimeout(() => {

                showModal(`
            <div class="text-center">

                <div class="text-4xl mb-3">🎉</div>

                <h3 class="text-sm font-semibold mb-2">
                    Recompensa desbloqueada!
                </h3>

                <p class="text-xs text-zinc-400 mb-4">
                    Você já tem <span class="text-amber-400 font-medium">até 500 moedas</span> disponíveis.<br>
                    Falta só um passo para liberar.
                </p>

                <button onclick="unlockReward()"
                    class="w-full inline-flex items-center justify-center
                    px-4 py-3 rounded-xl
                    bg-amber-400 hover:bg-amber-300
                    text-zinc-900 text-sm font-semibold
                    animate-pulse">

                    ⚡ Liberar minhas moedas

                </button>

                <div class="text-[10px] text-zinc-500 mt-3">
                    leva menos de 1 minuto
                </div>

            </div>
        `);

            }, 800);

            // 👉 já prepara CTA fora do modal
            showChestOpenedCTA();
        }

        function unlockReward() {

            showModal(`
        <div class="text-center">

            <div class="text-3xl mb-3">⚡</div>

            <h3 class="text-sm font-semibold mb-2">
                Falta só 1 passo
            </h3>

            <p class="text-xs text-zinc-400 mb-4">
                Para liberar suas moedas, acesse a página abaixo e volte em seguida.
            </p>

            <button onclick="goOffer()"
                class="w-full px-4 py-3 rounded-xl
                bg-amber-400 text-zinc-900 font-semibold
                animate-pulse">

                🚀 Liberar minhas moedas

            </button>

            <div class="text-[10px] text-zinc-500 mt-3">
                rápido • gratuito • menos de 1 minuto
            </div>

        </div>
    `);

        }

        function goOffer() {

            let url = "https://omg10.com/4/10807758"; // Link Monetização Baú aqui

            gtag('event', 'offer_click', {
                type: 'main_unlock_flow'
            });

            fbq('track', 'InitiateCheckout');

            window.open(url, "_blank");

            setTimeout(() => {
                showRewardUnlocked();
            }, 1200);
        }


        function showRewardUnlocked() {

            showModal(`
                <div class="text-center">

                    <div class="text-4xl mb-3">🎉</div>

                    <h3 class="text-sm font-semibold mb-2">
                        Recompensa desbloqueada!
                    </h3>

                    <p class="text-xs text-zinc-400 mb-4">
                        Seu prêmio está disponível. Toque abaixo para resgatar agora.
                    </p>

                    <a href="https://www.tiktok.com/d/1/ZS98rbTC1jkY2-C1dfa/"
                        target="_blank"
                        class="w-full inline-flex items-center justify-center
                        px-4 py-3 rounded-xl
                        bg-amber-400 text-zinc-900 font-semibold">

                        🎁 Resgatar recompensa
                    </a>

                    <div class="mt-3">
                        <a href="/recompensas"
                            class="text-xs text-amber-400 hover:underline">
                            ou continuar ganhando mais
                        </a>
                    </div>

                </div>
            `);

        }

        const todayKey = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
        let chestOpened = localStorage.getItem('chest_opened') === todayKey;

        function applyChestState() {
            const img = document.getElementById('chestImg');
            const btn = document.getElementById('chestBtn');

            if (chestOpened) {
                img.src = "{{ asset('images/chest-open.png') }}";
                img.classList.remove('chest-pulse');
                img.classList.add('chest-opened');

                btn.disabled = true;

                showChestOpenedCTA();
            }
        }

        function showChestOpenedCTA() {

            if (document.getElementById('chestAfterCTA')) return;

            const container = document.querySelector('#chestBtn').parentNode;

            container.insertAdjacentHTML('beforeend', `
                <a href="/recompensas"
                    id="chestAfterCTA"
                    class="mt-2 text-xs text-amber-400
                        hover:underline text-center block">

                    + continuar ganhando recompensas

                </a>
            `);
        }

        let pollInterval = 800;
        let pollTimer = null;

        function startPolling() {

            if (pollTimer) return;

            pollTimer = setInterval(checkMatchStatus, pollInterval);

            setTimeout(() => {

                clearInterval(pollTimer);

                pollInterval = 2000;

                pollTimer = setInterval(checkMatchStatus, pollInterval);

            }, 10000);
        }

        let opponentLoaded = false;
        let startButtonShown = false;
        let selectedGame = null;

        let matchId = {!! $match?->id ?? 'null' !!};

        @auth
        if (!matchId) {
            enterArena();
        } else {
            startPolling();
        }
        @endauth

        async function enterArena() {

            try {

                const res = await fetch('/arena/enter', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;

                const data = await res.json();

                matchId = data.match_id;

                startPolling();

                checkMatchStatus();

            } catch (e) {
                console.error('Arena enter error', e);
            }
        }

        async function openGameChooser() {
            const res = await fetch('/arena/games', {
                headers: {
                    Accept: 'application/json'
                }
            });

            if (!res.ok) {
                alert('Erro ao carregar jogos');
                return;
            }

            const games = await res.json();

            if (!games.length) {
                alert('Nenhum jogo disponível no momento');
                return;
            }


            let list = games.map(game => `
    <button
        onclick="chooseGame('${game.code}')"
        class="w-full text-left px-3 py-2 rounded-lg
               hover:bg-amber-400 hover:text-zinc-900
               transition text-xs">
        <div class="font-medium">${game.name}</div>
        <div class="text-[10px] opacity-70">
            ${game.category} • ${game.min_players}v${game.max_players}
        </div>
    </button>
`).join('');

            showModal(`
        <h3 class="text-sm font-semibold mb-3">Escolha um jogo</h3>
        <div class="flex flex-col gap-2">${list}</div>
    `);
        }

        async function startGame() {

            if (!matchId) return;
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

        async function chooseGame(code) {

            showModal(`
    <div class="text-center">
        <div class="text-2xl mb-2">🎮</div>
        <p class="text-sm font-medium">Jogo selecionado!</p>
        <p class="text-[11px] text-zinc-400 mt-1">
            Avisando o outro jogador…
        </p>
    </div>
`);
            const res = await fetch(`/arena/choose-game/${matchId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    game_code: code
                })
            });

            if (!res.ok) {
                alert('Não foi possível escolher o jogo');
                return;
            }

            location.reload();
        }


        async function checkMatchStatus() {

            if (!matchId) return;
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

            // Atualiza o slot do oponente
            if (data.opponent) {
                hydrateSlot(opponentSlot, data.opponent, data.game_name || data.game_code);
            }

            // Atualiza o slot do próprio jogador para refletir o jogo
            hydrateSlot(mySlot, data.me, data.game_name || data.game_code);

            // Atualiza status
            updateMatchStatus(data);

            // Mostrar jogo escolhido dinamicamente para ambos
            if (data.game_code) {

                if (!document.getElementById('gameSelectedInfo')) {

                    const statusEl = document.getElementById('matchStatus');
                    const container = statusEl.parentNode;

                    container.insertAdjacentHTML('beforeend', `
            <div id="gameSelectedInfo"
                class="text-center text-xs text-amber-400 mt-3">
                🎮 Jogo escolhido: ${data.game_name || data.game_code}
            </div>
        `);
                }
            }

            if (data.opponent && !data.game_code) {

                if (!document.getElementById('gameChooser')) {

                    const statusEl = document.getElementById('matchStatus');
                    const container = statusEl.parentNode;

                    container.insertAdjacentHTML('beforeend', `
            <div id="gameChooser" class="mt-3">
                <button onclick="openGameChooser()"
                    class="w-full px-4 py-3 rounded-xl
                    bg-amber-400 text-zinc-900 font-semibold
                    hover:bg-amber-300 hover:scale-105
                    transition-all duration-200">
                    Escolher jogo
                </button>
            </div>
        `);
                }
            }

            if (data.game_code) {
                const chooser = document.getElementById('gameChooser');
                if (chooser) chooser.remove();
            }

            // Mostra botão iniciar se ambos prontos e jogo selecionado
            const btn = document.getElementById('startGameBtn');
            if (data.opponent && data.game_code && btn) btn.classList.remove('hidden');

            // Remove botão convidar amigo se o segundo jogador entrou
            if (document.getElementById('inviteBtn') && data.opponent) {
                document.getElementById('inviteBtn').remove();
            }
        }


        function updateMatchStatus(data) {
            const el = document.getElementById('matchStatus');
            if (!el) return;

            if (data.opponent && data.game_code) {
                el.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Pronto
        `;
            } else if (data.opponent && !data.game_code) {
                el.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Escolha um jogo
        `;
            } else {
                el.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            Aguardando oponente
        `;
            }
        }


        function showStartButton() {
            const btn = document.getElementById('startGameBtn');
            if (btn) btn.classList.remove('hidden');
        }

        function showModal(html) {
            const modal = document.getElementById('genericModal');
            const content = document.getElementById('genericModalContent');

            content.innerHTML = html;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('genericModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        async function leaveMatch() {
            if (!matchId) return;

            const res = await fetch(`/arena/leave/${matchId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                alert('Erro');
                return;
            }

            location.reload();
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

        document.addEventListener('DOMContentLoaded', () => {
            applyChestState();
        });


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
