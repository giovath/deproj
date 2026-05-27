<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <title>Mapa do Tesouro</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        body {
            min-height: 100dvh;
            background-image: url('/images/treasure-map.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #000;
            overflow: hidden;
            position: relative;
        }

        .map-container {
            position: relative;
            width: 100%;
            min-height: 100dvh;
        }

        /* SVG ocupa a tela inteira */
        .map-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* X do tesouro */
        .treasure-x {
            fill: none;
            stroke: #8b1e1e;
            stroke-width: 7;
            stroke-linecap: round;
            filter: drop-shadow(1px 2px 2px rgba(0, 0, 0, 0.25));
        }

        /* Caminho pontilhado */
        .path-line {
            fill: none;
            stroke: #6d3b16;
            stroke-width: 4;
            stroke-dasharray: 12 10;
            stroke-linecap: round;
            opacity: 0.8;
        }

        /* Espaços para missões */
        .mission {
            position: absolute;
            width: 72px;
            height: 72px;
            z-index: 2;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 2px dashed rgba(90, 45, 12, 0.5);
            backdrop-filter: blur(2px);

            overflow: visible;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* posições mobile-first */
        .mission-1 {
            top: 16%;
            left: 24%;
        }

        .mission-2 {
            top: 45%;
            right: 20%;
        }

        .mission img {
            width: 130%;
            max-width: none;
            height: auto;
            display: block;
            pointer-events: none;
            filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.25));
        }

        /* baú final */
        .chest {
            position: absolute;
            width: 110px;
            z-index: 3;
            bottom: 12%;
            left: 43%;
            transform: translateX(-50%);
        }

        .chest img {
            width: 100%;
            display: block;
        }

        /* leve pulsação futura */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.08);
                opacity: 0.85;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* tablet */
        @media (min-width: 768px) {
            .mission {
                width: 90px;
                height: 90px;
            }

            .chest {
                width: 150px;
            }
        }

        .locked {
            filter: grayscale(1);
            opacity: 0.45;
        }

        .locked-disabled {
            pointer-events: none;
        }

        .map-title {
            position: absolute;
            top: 4%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            text-align: center;
            width: 90%;
            pointer-events: none;
        }

        .map-title h1 {
            font-family: 'Cinzel', serif;
            font-size: clamp(1.8rem, 5vw, 3rem);
            font-weight: 700;
            color: #4a2c12;
            letter-spacing: 1px;
            text-shadow:
                1px 1px 0 rgba(255, 240, 200, 0.25),
                2px 2px 4px rgba(0, 0, 0, 0.15);
            margin-bottom: 4px;
        }

        .map-title p {
            font-family: 'Cinzel', serif;
            font-size: clamp(0.9rem, 2.8vw, 1.3rem);
            font-weight: 600;
            color: #6b3d1c;
            letter-spacing: 3px;
            text-transform: lowercase;
            opacity: 0.9;
        }

        .x-locked {
            stroke: #777;
            opacity: 0.5;
        }
    </style>
</head>

<body>

    <div class="map-container">

        <div class="map-title">
            <h1>Mapa do Tesouro</h1>
            <p>clickpremio</p>
        </div>

        <!-- SVG com caminho e X -->
        <svg class="map-svg" viewBox="0 0 1000 1800" preserveAspectRatio="none">

            <!-- caminho -->
            <path class="path-line" d="M200,350
                   C350,500 500,450 650,700
                   C760,900 600,1100 500,1350" />

            <!-- X 1 -->
            <g transform="translate(180,320)">
                <line class="treasure-x" x1="0" y1="0" x2="58" y2="58" />
                <line class="treasure-x" x1="58" y1="0" x2="0" y2="58" />
            </g>

            <!-- X 2 -->
            <g id="x2" transform="translate(635,700) rotate(5)">
                <line class="treasure-x x-locked" x1="0" y1="0" x2="58" y2="58" />
                <line class="treasure-x x-locked" x1="58" y1="0" x2="0" y2="58" />
            </g>

        </svg>

        <!-- Espaços das missões -->
        <a id="mission1" class="mission mission-1 pulse" href="/ilha-da-fortuna">
            <img src="/images/treasure-island.png" alt="Ilha da Fortuna">
        </a>

        <a id="mission2" class="mission mission-2 locked locked-disabled" href="/navio">

            <img src="/images/treasure-tripulation.png" alt="Tripulação do Tesouro">

        </a>

        <!-- Baú final -->
        <div id="treasureChest" class="chest locked">
            <img src="/images/chest-closed.png" alt="Baú do Tesouro">
        </div>

    </div>

    <script>
        const mission1 = document.getElementById('mission1');
        const mission2 = document.getElementById('mission2');

        const RESET_HOURS = 24;

        checkMissionReset();

        if (localStorage.getItem('mission1_completed') === 'true') {

            unlockMission2();

        }

        function unlockMission2() {

            mission1.classList.remove('pulse');

            mission1.style.opacity = '0.7';

            mission1.style.pointerEvents = 'none';

            mission1.style.cursor = 'default';

            mission2.classList.remove('locked');

            mission2.classList.remove('locked-disabled');

            mission2.classList.add('pulse');

            document.querySelectorAll('#x2 .treasure-x').forEach(line => {

                line.classList.remove('x-locked');

            });

        }

        function checkMissionReset() {

            const completedAt = localStorage.getItem('mission1_completed_at');

            if (!completedAt) {
                return;
            }

            const now = Date.now();

            const diff = now - Number(completedAt);

            const hoursPassed = diff / (1000 * 60 * 60);

            if (hoursPassed >= RESET_HOURS) {

                localStorage.removeItem('mission1_completed');

                localStorage.removeItem('mission1_completed_at');

            }

        }
    </script>

</body>

</html>
