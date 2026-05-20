<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ilha da Fortuna</title>

    <!-- MULTITAG -->
    <script src="https://quge5.com/88/tag.min.js" data-zone="241313" async data-cfasync="false"></script>

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
                linear-gradient(rgba(0, 0, 0, .45),
                    rgba(0, 0, 0, .55)),
                url('/images/treasure-island-bg.jpg');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            font-family: Arial, sans-serif;
        }

        .container {

            width: 92%;
            max-width: 420px;

            padding: 28px;

            border-radius: 24px;

            background: rgba(20, 12, 5, 0.78);

            border: 2px solid rgba(194, 139, 44, .25);

            backdrop-filter: blur(6px);

            text-align: center;

            color: #f5deb3;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, .45);
        }

        h1 {

            font-family: 'Cinzel', serif;

            font-size: 2rem;

            margin-bottom: 16px;

            color: #f0c36a;
        }

        #status {

            line-height: 1.6;

            font-size: 1rem;

            min-height: 52px;

            opacity: .92;
        }

        .loader {

            width: 70px;
            height: 70px;

            margin: 28px auto;

            border-radius: 50%;

            border: 5px solid rgba(255, 255, 255, .12);

            border-top-color: #d4a64a;

            animation: spin 1.1s linear infinite;
        }

        @keyframes spin {

            to {
                transform: rotate(360deg);
            }

        }

        #rewardButton {

            display: none;

            width: 100%;

            padding: 16px;

            border: none;

            border-radius: 14px;

            background: linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            color: #fff;

            font-size: 1rem;
            font-weight: bold;

            cursor: pointer;

            transition: .2s;
        }

        #rewardButton:active {

            transform: scale(.98);

        }
    </style>

</head>

<body>

    <div class="container">

        <h1>Ilha da Fortuna</h1>

        <p id="status">

            Explorando ruínas antigas...

        </p>

        <div class="loader"></div>

        <button id="rewardButton">

            Coletar Recompensa

        </button>

    </div>

    <script>
        const statusText =
            document.getElementById('status');

        const rewardButton =
            document.getElementById('rewardButton');

        // sequência da missão

        setTimeout(() => {

            statusText.innerText =
                'Encontrando pistas escondidas...';

        }, 2500);

        setTimeout(() => {

            statusText.innerText =
                'Tesouro localizado!';

        }, 5000);

        setTimeout(() => {

            document
                .querySelector('.loader')
                .style.display = 'none';

            rewardButton.style.display =
                'block';

        }, 6500);

        // conclusão da missão

        rewardButton.addEventListener('click', () => {

            statusText.innerText =
                'Coletando recompensa...';

            rewardButton.style.display =
                'none';

            setTimeout(() => {

                localStorage.setItem(
                    'mission1_completed',
                    'true'
                );

                localStorage.setItem(
                    'mission1_completed_at',
                    Date.now()
                );

                window.location.href = '/';

            }, 1800);

        });
    </script>

</body>

</html>
