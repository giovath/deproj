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
                linear-gradient(rgba(0, 0, 0, .75),
                    rgba(0, 0, 0, .85)),
                url('/images/treasure-room-bg.jpg');

            background-size: cover;

            background-position: center;

            font-family: Arial, sans-serif;

            padding: 20px;
        }

        .container {

            width: 100%;

            max-width: 460px;

            background: rgba(20, 12, 5, .92);

            border-radius: 24px;

            border: 2px solid rgba(212, 170, 74, .25);

            padding: 30px;

            color: #f5deb3;

            text-align: center;
        }

        h1 {

            color: #f0c36a;

            margin-bottom: 20px;
        }

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

        .reward-list {

            margin-top: 12px;

            display: flex;

            flex-direction: column;

            gap: 6px;

            text-align: left;
        }

        .reward-item {

            display: flex;

            justify-content: space-between;

            background: rgba(255, 255, 255, .05);

            padding: 8px 12px;

            border-radius: 10px;
        }

        button {

            width: 100%;

            border: none;

            border-radius: 16px;

            padding: 16px;

            font-size: 1rem;

            font-weight: bold;

            cursor: pointer;

            color: white;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            transition: .2s;

            margin-top: 12px;
        }

        button:hover {

            transform: translateY(-2px);
        }

        .secondary {

            background:

                linear-gradient(180deg,
                    #4b82d6,
                    #2757b8);
        }

        .timer {

            color: #f0c36a;

            font-weight: bold;

            margin-top: 6px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>⚓ Porto do Tesouro</h1>

        <div class="wallet">

            <div class="wallet-title">

                💰 Ouro do Capitão

            </div>

            <div class="wallet-value" id="coins">

                {{ $coins }}

            </div>

            <div>

                moedas disponíveis

            </div>

        </div>

        <div class="section">

            <h2>🎁 Próxima Expedição Gratuita</h2>

            <div class="timer">

                Em breve...

            </div>

            <div class="small">

                Em uma próxima atualização este tempo será contabilizado automaticamente.

            </div>

        </div>

        <div class="section">

            <h2>⚓ Expedições</h2>

            <p>

                Participações disponíveis:

                <strong id="participations">

                    {{ $participations }}

                </strong>

            </p>

            <div class="small">

                Cada expedição permite explorar os mares durante até
                <strong>15 minutos</strong>.

                Ao concluir sua jornada você conquista uma
                <strong>Relíquia</strong> e participa do Ranking Semanal dos Capitães.

            </div>

        </div>

        <div class="section">

            <h2>🏆 Ranking Semanal dos Capitães</h2>

            <p>

                Quanto mais expedições você concluir, maiores serão suas chances de conquistar os primeiros lugares.

            </p>

            <div class="reward-list">

                <div class="reward-item">

                    <span>🥇 1º Lugar</span>

                    <strong>R$ 30</strong>

                </div>

                <div class="reward-item">

                    <span>🥈 2º Lugar</span>

                    <strong>R$ 10</strong>

                </div>

                <div class="reward-item">

                    <span>🥉 3º Lugar</span>

                    <strong>R$ 5</strong>

                </div>

            </div>

        </div>

        <button id="buyParticipationButton">

            ⚓ Comprar Participação (100 moedas)

        </button>

        <button id="playButton" class="secondary">

            🗺️ Iniciar Expedição

        </button>

    </div>

    <script>
        const buyParticipationButton =
            document.getElementById('buyParticipationButton');

        const playButton =
            document.getElementById('playButton');

        const coins =
            document.getElementById('coins');

        const participations =
            document.getElementById('participations');

        buyParticipationButton.addEventListener('click', () => {

            fetch('/porto/comprar-participacao', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': '{{ csrf_token() }}'

                    }

                })

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {

                        alert(data.message);

                        return;
                    }

                    coins.innerText = data.coins;

                    participations.innerText = data.participations;

                });

        });

        playButton.addEventListener('click', () => {

            fetch('/porto/usar-participacao', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': '{{ csrf_token() }}'

                    }

                })

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {

                        alert(data.message);

                        return;
                    }

                    participations.innerText = data.participations;

                    // Na próxima etapa mudaremos para /games
                    window.location.href = '/jogos';

                });

        });
    </script>

</body>

</html>
