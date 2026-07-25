<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')

    <title>{{ __('messages.tesouro_encontrado') }}</title>

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

        <h1>🏆 {{ __('messages.tesouro_encontrado') }}</h1>

        <p class="description">

            {{ __('messages.tesouro_descricao') }}

        </p>

        <div class="chest">

            <img id="chestImage" src="/images/chest-closed.webp">

        </div>

        <div class="status" id="status">

            {{ __('messages.tesouro_aguarda') }}

        </div>

        <div class="coins" id="coins">

            0
        </div>

        <button id="openButton">

            🗝️ {{ __('messages.abrir_bau') }}

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
                "{{ __('messages.destravando_fechaduras') }}";

            setTimeout(() => {

                status.innerText =
                    "{{ __('messages.revelando_tesouro') }}";

            }, 1000);

            setTimeout(() => {

                chestImage.src =
                    '/images/chest-open.webp';

                status.innerText =
                    "{{ __('messages.recompensa_revelada') }}";

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
                            "{{ __('messages.tesouro_ja_coletado') }}";

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
                        target + ' {{ __('messages.moedas') }}';

                    status.innerText =
                        "{{ __('messages.encontrou_moedas') }}"
                        .replace(':count', target);

                    openButton.innerText =
                        "{{ __('messages.ir_docas') }}";

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
