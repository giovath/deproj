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

            text-decoration: none;

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
    </style>

</head>

<body>

    <div class="container">

        <div class="topbar">

            <h1>⚓ Porto</h1>

            @guest

                <a href="{{ route('auth.tiktok.redirect') }}" class="login-button">

                    Entrar

                </a>
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

                            <span>

                                Capitão {{ $item->captain->id }}

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



            <button onclick="location.href='/jogos'">

                🎮 Explorar Jogos

            </button>

        </div>

    </body>

    </html>
