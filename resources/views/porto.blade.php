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

            max-width: 450px;

            background: rgba(20, 12, 5, .90);

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

        .wallet {

            background: rgba(255, 255, 255, .05);

            border-radius: 16px;

            padding: 18px;

            margin-bottom: 18px;
        }

        .wallet-value {

            font-size: 2rem;

            font-weight: bold;

            color: #f0c36a;
        }

        .section {

            background: rgba(255, 255, 255, .05);

            border-radius: 16px;

            padding: 18px;

            margin-bottom: 18px;
        }

        .section h2 {

            margin-bottom: 10px;

            color: #f0c36a;

            font-size: 1.1rem;
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
        }

        .small {

            opacity: .8;

            font-size: .9rem;

            margin-top: 8px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>⚓ Porto do Tesouro</h1>

        <div class="wallet">

            <div>Saldo disponível</div>

            <div class="wallet-value" id="coins">
                {{ $coins }}
            </div>

            <div>moedas</div>

        </div>

        <div class="section">

            <h2>🎟️ Participações</h2>

            <p>
                Participações disponíveis:
                <strong id="entries">
                    {{ $entries }}
                </strong>
            </p>

            <p class="small">
                Cada participação custa 100 moedas.
            </p>

        </div>

        <div class="section">

            <h2>🏆 Ranking Semanal</h2>

            <p>
                Jogue, envie sua pontuação e dispute
                prêmios com outros capitães.
            </p>

        </div>

        <button id="buyEntryButton">

            Comprar Participação (100 moedas)

        </button>

    </div>

    <script>
        const buyEntryButton =
            document.getElementById('buyEntryButton');

        const coins =
            document.getElementById('coins');

        const entries =
            document.getElementById('entries');

        buyEntryButton.addEventListener('click', () => {

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

                    coins.innerText =
                        data.coins;

                    entries.innerText =
                        data.entries;

                });

        });
    </script>

</body>

</html>
