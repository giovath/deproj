<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')

    <title>Tesouro Encontrado</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            min-height: 100dvh;

            background:
                linear-gradient(rgba(0, 0, 0, .65),
                    rgba(0, 0, 0, .80)),
                url('/images/treasure-room-bg.jpg');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family: Arial, sans-serif;
        }

        .container {

            width: 100%;
            max-width: 430px;

            background: rgba(20, 12, 5, .88);

            border-radius: 26px;

            border: 2px solid rgba(212, 170, 74, .25);

            padding: 30px;

            text-align: center;

            color: #f5deb3;
        }

        h1 {

            font-family: 'Cinzel', serif;

            color: #f0c36a;

            margin-bottom: 16px;
        }

        .description {

            line-height: 1.6;

            opacity: .9;

            margin-bottom: 24px;
        }

        .chest {

            margin-bottom: 24px;
        }

        .chest img {

            width: 180px;

            max-width: 100%;
        }

        .status {

            min-height: 60px;

            margin-bottom: 20px;

            line-height: 1.5;
        }

        .coins {

            display: none;

            font-size: 2rem;

            font-weight: bold;

            color: #f0c36a;

            margin-bottom: 20px;
        }

        button {

            width: 100%;

            border: none;

            border-radius: 16px;

            padding: 16px;

            font-size: 1rem;

            font-weight: bold;

            color: white;

            cursor: pointer;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>🏆 Tesouro Encontrado</h1>

        <p class="description">

            Após atravessar a Ilha da Fortuna
            e reunir sua tripulação,
            você encontrou o Baú Perdido.

        </p>

        <div class="chest">

            <img id="chestImage" src="/images/chest-closed.webp">

        </div>

        <div class="status" id="status">

            O tesouro aguarda sua abertura.

        </div>

        <div class="coins" id="coins">

            0
        </div>

        <button id="openButton">

            🗝️ Abrir Baú

        </button>

    </div>

    <script>
        const openButton =
            document.getElementById('openButton');

        const chestImage =
            document.getElementById('chestImage');

        const status =
            document.getElementById('status');

        const coins =
            document.getElementById('coins');


        openButton.addEventListener('click', () => {

            openButton.disabled = true;

            status.innerText =
                '🔓 Destravando fechaduras...';

            setTimeout(() => {

                status.innerText =
                    '✨ Revelando tesouro...';

            }, 1000);

            setTimeout(() => {

                chestImage.src =
                    '/images/chest-open.webp';

                status.innerText =
                    '🪙 Tesouro encontrado! Sua recompensa está sendo revelada...';

                coins.style.display =
                    'block';


                collectTreasure();

            }, 2200);

        });

        function collectTreasure() {

            fetch('/tesouro/coletar', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }

                })

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {

                        status.innerText =
                            '⚠️ Tesouro já coletado.';

                        return;
                    }

                    animateCoins(data.coins);

                });

        }

        function animateCoins(target) {

            let current = 0;

            const interval = setInterval(() => {

                current += 4;

                if (current >= target) {

                    current = target;

                    clearInterval(interval);

                    coins.innerText =
                        target + ' moedas';

                    status.innerText =
                        '✨ Você encontrou ' + target + ' moedas!';

                    openButton.innerText =
                        '⚓ Ir para as Docas';

                    openButton.disabled = false;

                    openButton.onclick = () => {

                        window.location.href = '/porto';

                    };

                    return;
                }

                coins.innerText =
                    current + ' moedas';


            }, 20);

        }
    </script>

</body>

</html>
