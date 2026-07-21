<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')

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

            <h1>⚓ {{ __('messages.porto') }}</h1>

            @guest

                <button class="login-button" data-event="login_click" onclick="openLoginModal()">

                    {{ __('messages.entrar') }}

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

                💰 {{ __('messages.ouro_capitao') }}

            </div>

            <div class="wallet-value">

                {{ $coins }}

            </div>

            <div>

                {{ __('messages.moedas_disponiveis') }}

            </div>

        </div>



        <div class="wallet">

            <div class="wallet-title">

                🏺 {{ __('messages.reliquias') }}

            </div>

            <div class="wallet-value">

                {{ $relics }}

            </div>

            <div>

                {{ __('messages.reliquias_conquistadas') }}

            </div>

        </div>



        <div class="section">

            <h2>

                ⚓ {{ __('messages.expedicoes_premiadas') }}

            </h2>

            <p>

                {{ __('messages.possui_participacoes', [
                    'count' => $participations,
                ]) }}

            </p>

            <div class="small">

                {{ __('messages.use_participacoes') }}

            </div>

        </div>

        @auth

            @if ($treasureState['available'])
                <div class="section">

                    <h2>🎁 {{ __('messages.bau_diario') }}</h2>

                    <p>
                        {{ __('messages.daily_chest_available') }}
                    </p>

                    <a href="/" class="explore-button" style="text-decoration:none;text-align:center;display:block;">

                        🗺️ {{ __('messages.buscar_tesouro') }}

                    </a>

                </div>
            @else
                <div class="section">

                    <h2>🎁 {{ __('messages.bau_diario') }}</h2>

                    <p>
                        {{ __('messages.next_chest_available') }}
                    </p>

                    <div class="wallet-value" style="font-size:1.4rem">

                        {{ $treasureState['remaining'] }}

                    </div>

                </div>
            @endif

        @endauth


        @guest

            <div class="section">

                🎁 {{ __('messages.bau_tesouro') }}

                <p>

                    {{ __('messages.explore_map') }}

                </p>

                <div class="small">

                    {{ __('messages.login_unlock') }}

                </div>

            </div>

        @endguest

        <div class="section reward-box">

            <h2>
                🎁 {{ __('messages.weekly_rewards') }}
            </h2>


            <div class="reward-list">

                <div class="reward-item">

                    <span>
                        🥇 {{ __('messages.first_captain') }}
                    </span>

                    <strong>
                        R$ 20
                    </strong>

                </div>


                <div class="reward-item">

                    <span>
                        🥈 {{ __('messages.second_captain') }}
                    </span>

                    <strong>
                        R$ 10
                    </strong>

                </div>


                <div class="reward-item">

                    <span>
                        🥉 {{ __('messages.third_captain') }}
                    </span>

                    <strong>
                        R$ 5
                    </strong>

                </div>


            </div>


            <div class="small">

                {{ __('messages.compete_ranking') }}

            </div>


        </div>



        <div class="section">

            <h2>

                🏆 {{ __('messages.ranking_capitaes') }}

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

                                {{ $item->captain->user?->name ?? __('messages.anonymous_captain') }}

                            </span>

                        </div>

                        <strong>

                            {{ $item->relics }} 🏺

                        </strong>

                    </div>

                    @empty

                        <p>

                            {{ __('messages.no_captains') }}

                        </p>
                    @endforelse

                </div>

            </div>



            <button class="explore-button" data-event="games_open" onclick="location.href='/jogos'">

                🎮 {{ __('messages.explorar_jogos') }}

            </button>

        </div>

        <div id="loginModal" class="modal-overlay" onclick="closeLoginOutside(event)">


            <div class="modal-box">


                <h2>⚓ {{ __('messages.enter_port') }}</h2>

                <p>
                    {{ __('messages.login_description') }}
                </p>

                <a href="{{ route('auth.tiktok.redirect') }}" class="tiktok-login">

                    🎵 {{ __('messages.login_tiktok') }}

                </a>

                <p style="margin:18px 0 6px; font-size:.9rem; opacity:.75;">

                    {{ __('messages.no_account') }}

                </p>

                <a href="SEU_LINK_DE_AFILIADO" target="_blank" rel="noopener noreferrer" class="create-account-link">

                    {{ __('messages.create_account') }} →

                </a>

                <div class="close-modal" onclick="closeLoginModal()">

                    {{ __('messages.cancel') }}

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
