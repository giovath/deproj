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
                    rgba(0, 0, 0, .65)),
                url('/images/treasure-island-bg.jpg');

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
            max-width: 420px;

            background: rgba(20, 12, 5, .82);

            border: 2px solid rgba(194, 139, 44, .25);

            border-radius: 24px;

            padding: 28px;

            backdrop-filter: blur(8px);

            color: #f5deb3;

            text-align: center;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, .45);
        }

        h1 {

            font-family: 'Cinzel', serif;

            color: #f0c36a;

            font-size: 2rem;

            margin-bottom: 14px;
        }

        .description {

            line-height: 1.6;

            opacity: .9;

            margin-bottom: 24px;
        }

        .mission-box {

            display: flex;
            flex-direction: column;
            gap: 14px;

            margin-bottom: 24px;
        }

        .mission-link {

            display: flex;
            align-items: center;
            justify-content: space-between;

            text-decoration: none;

            padding: 16px 18px;

            border-radius: 16px;

            background: rgba(255, 255, 255, .06);

            border: 1px solid rgba(255, 255, 255, .08);

            color: #fff;

            font-weight: bold;

            transition: .2s;
        }

        .mission-link:active {

            transform: scale(.98);
        }

        .mission-link span {

            opacity: .7;
        }

        .status {

            margin-top: 10px;

            font-size: .95rem;

            opacity: .85;

            min-height: 24px;
        }

        #completeButton {

            width: 100%;

            margin-top: 22px;

            padding: 16px;

            border: none;

            border-radius: 16px;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            color: #fff;

            font-size: 1rem;

            font-weight: bold;

            cursor: pointer;

        }

        #progressText {

            margin-top: 18px;

            font-size: .95rem;

            opacity: .75;
        }

        #progressText {

            margin-top: 18px;

            font-size: .95rem;

            opacity: .75;
        }

        #completeButton {

            width: 100%;

            margin-top: 24px;

            padding: 16px;

            border: none;

            border-radius: 16px;

            background: #6e5a2d;

            color: #fff;

            font-size: 1rem;

            font-weight: bold;

            opacity: .5;

            cursor: not-allowed;

            transition: .25s;
        }

        #completeButton.active {

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            opacity: 1;

            cursor: pointer;
        }

        .explore-link.done {

            opacity: .5;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>Ilha da Fortuna</h1>

        <p class="description">

            Os guardiões da ilha esconderam o tesouro principal.

            Antes de retornar ao mapa, explore os locais secretos abaixo para encontrar pistas valiosas.

        </p>

        <div class="mission-box">

            <a href="#" class="mission-link explore-link">

                ⛏️ Visitar Minas de Ouro

                <span>↗</span>

            </a>

            <a href="#" class="mission-link explore-link">

                🕯️ Explorar Caverna Encantada

                <span>↗</span>

            </a>

        </div>

        <p id="progressText">

            0 / 2 locais explorados

        </p>

        <p class="status" id="status">

            Explore os locais secretos da ilha.

        </p>

        <button id="completeButton" disabled>

            🔒 Desbloquear Próxima Missão

        </button>

    </div>

    <script>
        const exploreLinks =
            document.querySelectorAll('.explore-link');

        const status =
            document.getElementById('status');

        const progressText =
            document.getElementById('progressText');

        const completeButton =
            document.getElementById('completeButton');

        let exploredCount = 0;

        exploreLinks.forEach(link => {

            link.addEventListener('click', () => {

                // impede múltiplos cliques no mesmo item
                if (link.classList.contains('done')) {
                    return;
                }

                link.classList.add('done');

                exploredCount++;

                progressText.innerText =
                    exploredCount + ' / 2 locais explorados';

                // visual concluído
                link.style.opacity = '.55';

                link.style.pointerEvents = 'none';

                // mensagens temáticas
                if (exploredCount === 1) {

                    status.innerText =
                        'Boa! Você encontrou uma pista do tesouro principal.';

                }

                if (exploredCount >= 2) {

                    status.innerText =
                        'Mapa completo! A próxima missão foi desbloqueada.';

                    completeButton.disabled = false;

                    completeButton.classList.add('active');

                }

            });

        });

        const exploreLinks =
            document.querySelectorAll('.explore-link');

        const status =
            document.getElementById('status');

        const progressText =
            document.getElementById('progressText');

        const completeButton =
            document.getElementById('completeButton');

        let exploredCount = 0;

        exploreLinks.forEach(link => {

            link.addEventListener('click', () => {

                if (link.classList.contains('done')) {
                    return;
                }

                link.classList.add('done');

                exploredCount++;

                progressText.innerText =
                    exploredCount + ' / 2 locais explorados';

                link.style.opacity = '.55';

                link.style.pointerEvents = 'none';

                if (exploredCount === 1) {

                    status.innerText =
                        'Boa! Você encontrou uma pista rara da ilha.';

                }

                if (exploredCount >= 2) {

                    status.innerText =
                        'Mapa completo! A próxima missão foi desbloqueada.';

                    completeButton.disabled = false;

                    completeButton.classList.add('active');

                    completeButton.innerText =
                        '🔓 Desbloquear Próxima Missão';

                }

            });

        });

        completeButton.addEventListener('click', () => {

            if (completeButton.disabled) {
                return;
            }

            localStorage.setItem(
                'mission1_completed',
                'true'
            );

            localStorage.setItem(
                'mission1_completed_at',
                Date.now()
            );

            window.location.href = '/';

        });
    </script>

</body>

</html>
