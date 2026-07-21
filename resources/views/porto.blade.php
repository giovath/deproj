<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Porto do Tesouro</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background:
                linear-gradient(rgba(0, 0, 0, .75), rgba(0, 0, 0, .85)),
                url('/images/treasure-room-bg.jpg');

            background-size: cover;
            background-position: center;

            font-family: Arial, sans-serif;

            padding: 20px;

        }

        .container {

            width: 100%;
            max-width: 470px;

            background: rgba(20, 12, 5, .92);

            border-radius: 24px;

            border: 2px solid rgba(212, 170, 74, .25);

            padding: 28px;

            color: #f5deb3;

        }


        /*==============================
            TOPO
        ==============================*/

        .topbar {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

        }

        h1 {

            color: #f0c36a;

            font-size: 1.6rem;

        }

        .profile {

            display: flex;

            align-items: center;

            gap: 10px;

        }

        .avatar {

            width: 42px;
            height: 42px;

            border-radius: 50%;

            object-fit: cover;

            border: 2px solid #d6a84d;

        }

        .profile-name {

            font-size: .9rem;

            color: #f5deb3;

            max-width: 120px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

        }

        .login-button {

            border: none;
            cursor: pointer;

            padding: 10px 16px;

            border-radius: 12px;

            background: #111;

            color: white;

            font-size: .9rem;

            transition: .2s;

        }

        .login-button:hover {

            background: #222;

        }


        /*==============================
            CARDS
        ==============================*/

        .wallet,
        .section {

            background: rgba(255, 255, 255, .05);

            border-radius: 16px;

            padding: 18px;

            margin-bottom: 18px;

        }

        .wallet-title {

            font-size: .95rem;

            opacity: .9;

        }

        .wallet-value {

            font-size: 2rem;

            font-weight: bold;

            color: #f0c36a;

            margin: 6px 0;

        }

        .section h2 {

            color: #f0c36a;

            margin-bottom: 10px;

            font-size: 1.1rem;

        }

        .section p {

            line-height: 1.5;

        }

        .small {

            margin-top: 10px;

            opacity: .75;

            font-size: .9rem;

        }


        /*==============================
            RANKING
        ==============================*/

        .reward-list {

            margin-top: 12px;

            display: flex;

            flex-direction: column;

            gap: 8px;

        }

        .reward-item {

            display: flex;

            justify-content: space-between;

            align-items: center;

            background: rgba(255, 255, 255, .05);

            padding: 10px 12px;

            border-radius: 10px;

        }

        .reward-left {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        /*==============================
            BOTÃO
        ==============================*/

        button {

            width: 100%;

            border: none;

            border-radius: 16px;

            padding: 16px;

            font-size: 1rem;

            font-weight: bold;

            cursor: pointer;

            color: white;

            background: linear-gradient(180deg, #d6a84d, #b98526);

            transition: .2s;

            margin-top: 12px;

        }

        button:hover {

            transform: translateY(-2px);

        }

        .ranking-avatar {

            width: 30px;
            height: 30px;

            border-radius: 50%;

            object-fit: cover;

            border: 2px solid #d6a84d;

        }

        /*==============================
    LOGIN MODAL
==============================*/

        .modal-overlay {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, .75);

            z-index: 100;

            justify-content: center;

            align-items: center;

            padding: 20px;

        }


        .modal-box {

            width: 100%;

            max-width: 350px;

            background: #140c05;

            border: 2px solid rgba(212, 170, 74, .4);

            border-radius: 20px;

            padding: 25px;

            text-align: center;

            color: #f5deb3;

        }


        .modal-box h2 {

            color: #f0c36a;

            margin-bottom: 15px;

        }


        .modal-box p {

            font-size: .95rem;

            line-height: 1.5;

            margin-bottom: 20px;

        }


        .tiktok-login {

            display: block;

            width: 100%;

            padding: 15px;

            border-radius: 14px;

            background: #000;

            color: white;

            text-decoration: none;

            font-weight: bold;

            font-size: 1rem;

            transition: .2s;

        }

        .tiktok-login:hover {

            transform: translateY(-2px);

        }


        .close-modal {

            margin-top: 15px;

            cursor: pointer;

            opacity: .7;

        }

        .create-account-link {

            display: inline-block;

            margin-top: 10px;

            color: #d6a84d;

            text-decoration: none;

            font-size: .92rem;

            transition: .2s;

        }

        .create-account-link:hover {

            color: #f0c36a;

            text-decoration: underline;

        }

        .explore-button {

            width: 100%;

            border: none;

            border-radius: 16px;

            padding: 16px;

            font-size: 1rem;

            font-weight: bold;

            cursor: pointer;

            color: white;

            background: linear-gradient(180deg, #d6a84d, #b98526);

            transition: .2s;

            margin-top: 12px;

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="topbar">

            <h1>⚓ Porto</h1>

            @guest

                <button class="login-button" data-event="login_click" onclick="openLoginModal()">

                    Entrar

                </button>
            @else
                <div class="profile">

                    <img class="avatar" src="{{ Auth::user()->avatar }}" alt="Avatar">

                    <div class="profile-name">

                        {{ Auth::user()->name }}

                    </div>

                </div>

            @endguest

        </div>



        <div class="wallet">

            <div class="wallet-title">

                💰 Ouro do Capitão

            </div>

            <div class="wallet-value">

                {{ $coins }}

            </div>

            <div>

                moedas disponíveis

            </div>

        </div>



        <div class="wallet">

            <div class="wallet-title">

                🏺 Relíquias

            </div>

            <div class="wallet-value">

                {{ $relics }}

            </div>

            <div>

                relíquias conquistadas

            </div>

        </div>



        <div class="section">

            <h2>

                ⚓ Expedições Premiadas

            </h2>

            <p>

                Você possui
                <strong>{{ $participations }}</strong>
                participação(ões).

            </p>

            <div class="small">

                Use participações para iniciar expedições e conquistar
                <strong>Relíquias</strong>.

            </div>

        </div>

        @auth

            @if ($treasureState['available'])
                <div class="section">

                    <h2>🎁 Baú Diário</h2>

                    <p>
                        Seu baú diário já está disponível.
                    </p>

                    <a href="/" class="explore-button" style="text-decoration:none;text-align:center;display:block;">

                        🗺️ Buscar Tesouro

                    </a>

                </div>
            @else
                <div class="section">

                    <h2>🎁 Baú Diário</h2>

                    <p>

                        Próximo baú disponível em

                    </p>

                    <div class="wallet-value" style="font-size:1.4rem">

                        {{ $treasureState['remaining'] }}

                    </div>

                </div>
            @endif

        @endauth


        @guest

            <div class="section">

                <h2>🎁 Baú do Tesouro</h2>

                <p>

                    Explore o mapa para encontrar um novo tesouro.

                </p>

                <div class="small">

                    Entre com sua conta para desbloquear o
                    <strong>Baú Diário</strong>
                    e salvar permanentemente seu progresso.

                </div>

            </div>

        @endguest



        <div class="section">

            <h2>

                🏆 Ranking dos Capitães

            </h2>

            <div class="reward-list">

                @forelse($ranking as $index => $item)
                    <div class="reward-item">

                        <div class="reward-left">

                            @switch($index)
                                @case(0)
                                    🥇
                                @break

                                @case(1)
                                    🥈
                                @break

                                @case(2)
                                    🥉
                                @break

                                @default
                                    🏴‍☠️
                            @endswitch
                            <img src="{{ $item->captain->user?->avatar ?? asset('images/avatar.png') }}"
                                class="ranking-avatar" alt="Avatar">

                            <span>

                                {{ $item->captain->user?->name ?? 'Capitão Anônimo' }}

                            </span>

                        </div>

                        <strong>

                            {{ $item->relics }} 🏺

                        </strong>

                    </div>

                    @empty

                        <p>

                            Ainda não existem capitães no ranking.

                        </p>
                    @endforelse

                </div>

            </div>



            <button class="explore-button" data-event="games_open" onclick="location.href='/jogos'">

                🎮 Explorar Jogos

            </button>

        </div>

        <div id="loginModal" class="modal-overlay" onclick="closeLoginOutside(event)">


            <div class="modal-box">


                <h2>⚓ Entrar no Porto</h2>

                <p>
                    Entre no Porto para salvar suas moedas, conquistar relíquias e competir entre os maiores capitães.
                </p>

                <a href="{{ route('auth.tiktok.redirect') }}" class="tiktok-login">

                    🎵 Entrar com TikTok

                </a>

                <p style="margin:18px 0 6px; font-size:.9rem; opacity:.75;">

                    Ainda não possui uma conta?

                </p>

                <a href="SEU_LINK_DE_AFILIADO" target="_blank" rel="noopener noreferrer" class="create-account-link">

                    Criar conta gratuitamente →

                </a>

                <div class="close-modal" onclick="closeLoginModal()">

                    Cancelar

                </div>


            </div>


        </div>

        <script>
            function openLoginModal() {

                document.getElementById('loginModal').style.display = 'flex';

            }


            function closeLoginModal() {

                document.getElementById('loginModal').style.display = 'none';

            }

            function closeLoginOutside(event) {

                if (event.target.id === 'loginModal') {

                    closeLoginModal();

                }

            }
        </script>

    </body>

    </html>
