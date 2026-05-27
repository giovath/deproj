<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ilha da Fortuna</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <script src="https://5gvci.com/act/files/tag.min.js?z=11045570" data-cfasync="false" async></script>

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




        #progressText {
            margin-top: 18px;
            font-size: .95rem;
            opacity: .9;
            color: #f0c36a;
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

        .progress-bar {

            width: 100%;
            height: 12px;

            background: rgba(255, 255, 255, .08);

            border-radius: 999px;

            overflow: hidden;

            margin-top: 18px;
        }

        .progress-fill {

            width: 0%;

            height: 100%;

            background: linear-gradient(90deg,
                    #d6a84d,
                    #f0c36a);

            transition: .35s;
        }

        #completeButton.active {
            animation: glow 2s infinite;
        }

        @keyframes glow {

            0% {
                box-shadow: 0 0 0 rgba(240, 195, 106, .0);
            }

            50% {
                box-shadow: 0 0 18px rgba(240, 195, 106, .45);
            }

            100% {
                box-shadow: 0 0 0 rgba(240, 195, 106, .0);
            }

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

            <a href="#" id="pushMission" class="mission-link">

                📡 Ativar Mensagens da Ilha

                <span>↗</span>

            </a>

            <a href="#" id="exploreMission" class="mission-link">

                ⛏️ Explorar Minas Perdidas

                <span>↗</span>

            </a>

        </div>

        <p id="progressText">

            0 / 2 locais explorados

        </p>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <p class="status" id="status">

            Explore os locais secretos da ilha.

        </p>

        <button id="completeButton" disabled>

            🔒 Desbloquear Próxima Missão

        </button>

    </div>

    <script>
        const pushMission =
            document.getElementById('pushMission');

        const exploreMission =
            document.getElementById('exploreMission');

        const status =
            document.getElementById('status');

        const progressText =
            document.getElementById('progressText');

        const progressFill =
            document.getElementById('progressFill');

        const completeButton =
            document.getElementById('completeButton');

        let exploredCount = 0;

        const TOTAL_MISSIONS = 2;

        // evita contar duas vezes
        let pushCompleted = false;
        let exploreCompleted = false;

        function updateProgress() {

            progressText.innerText =
                exploredCount + ' / ' +
                TOTAL_MISSIONS +
                ' locais explorados';

            const progressPercent =
                (exploredCount / TOTAL_MISSIONS) * 100;

            progressFill.style.width =
                progressPercent + '%';

            // primeira etapa
            if (exploredCount === 1) {

                status.innerText =
                    '🌊 Você encontrou uma pista rara da Ilha da Fortuna.';
            }

            // conclusão
            if (exploredCount >= TOTAL_MISSIONS) {

                status.innerText =
                    '🏴‍☠️ O mapa secreto foi desbloqueado.';

                completeButton.disabled = false;

                completeButton.classList.add('active');

                completeButton.innerText =
                    '🗺️ Desbloquear Próxima Missão';

                completeButton.animate([{
                        transform: 'scale(1)'
                    },
                    {
                        transform: 'scale(1.05)'
                    },
                    {
                        transform: 'scale(1)'
                    }
                ], {
                    duration: 500
                });
            }
        }

        // =========================
        // MISSÃO PUSH
        // =========================

        pushMission.addEventListener('click', async (event) => {

            event.preventDefault();

            if (pushCompleted) {
                return;
            }

            status.innerText =
                '📡 Ativando mensagens da Ilha...';

            if (!('Notification' in window)) {

                status.innerText =
                    '⚠️ Seu navegador não suporta mensagens da Ilha.';

                return;
            }

            try {

                const permission =
                    await Notification.requestPermission();

                if (permission !== 'granted') {

                    status.innerText =
                        '❌ Você recusou as mensagens da Ilha.';

                    return;
                }

                // ACEITOU
                pushCompleted = true;

                exploredCount++;

                pushMission.style.opacity = '.55';

                pushMission.style.pointerEvents = 'none';

                pushMission.querySelector('span').innerText = '✔';

                status.innerText =
                    '📜 As mensagens secretas foram ativadas.';

                updateProgress();

            } catch (error) {

                console.log(error);

                status.innerText =
                    '⚠️ Não foi possível ativar as mensagens.';
            }

        });

        // =========================
        // MISSÃO EXTERNA
        // =========================

        exploreMission.addEventListener('click', (event) => {

            event.preventDefault();

            if (exploreCompleted) {
                return;
            }

            exploreCompleted = true;

            exploredCount++;

            exploreMission.style.opacity = '.55';

            exploreMission.style.pointerEvents = 'none';

            exploreMission.querySelector('span').innerText = '✔';

            updateProgress();

            // LINK EXTERNO
            window.open(
                'https://omg10.com/4/10807758',
                '_blank',
                'noopener,noreferrer'
            );

        });

        // =========================
        // BOTÃO FINAL
        // =========================

        completeButton.addEventListener('click', () => {

            if (completeButton.disabled) {
                return;
            }

            completeButton.disabled = true;

            completeButton.innerText =
                '🧭 Retornando ao mapa...';

            fetch('/mission1/complete', {

                method: 'POST',

                credentials: 'same-origin',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }

            }).then(() => {

                window.location.href = '/';

            });

        });
    </script>

</body>

</html>
