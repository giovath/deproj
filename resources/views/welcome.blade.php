<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <link rel="preload" as="image" href="/images/treasure-map.webp">

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
            background-image: url('/images/treasure-map.webp');
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
            opacity: .45;
            pointer-events: none;
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

        .completed {

            opacity: .7;

            pointer-events: none;

            cursor: default;

        }

        .map-port {

            position: absolute;

            top: 5%;

            right: 5%;

            z-index: 10;

            display: flex;

            align-items: center;

            gap: 8px;

            padding: 10px 16px;

            border-radius: 999px;

            background: rgba(88, 58, 29, .28);

            backdrop-filter: blur(3px);

            border: 1px solid rgba(120, 80, 40, .25);

            color: #4a2c12;

            font-family: 'Cinzel', serif;

            font-size: .95rem;

            font-weight: 700;

            text-decoration: none;

            transition: .25s ease;

        }

        .map-port:hover {

            transform: translateY(-2px);

            background: rgba(110, 70, 30, .38);

            color: #2d1807;

        }

        @media (max-width: 600px) {

            .map-port {

                top: 18px;

                right: 16px;

                padding: 8px 12px;

                font-size: .82rem;

            }

        }

        .map-port {

            animation: harborFloat 4s ease-in-out infinite;

        }

        @keyframes harborFloat {

            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-2px);
            }

            100% {
                transform: translateY(0);
            }

        }
    </style>
</head>

<body>

    <div class="map-container">

        <a href="/porto" data-event="porto_open" class="map-port">

            ⚓ Porto

        </a>

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
        <a id="mission1" data-event="mission_1_start" class="mission mission-1 pulse" href="/ilha-da-fortuna">
            <img src="/images/treasure-island.webp" alt="Ilha da Fortuna">
        </a>

        <a id="mission2" data-event="mission_2_start" class="mission mission-2 locked locked-disabled" href="/navio">

            <img src="/images/treasure-tripulation.webp" alt="Tripulação do Tesouro">

        </a>

        <!-- Baú final -->
        <div id="treasureChest" data-event="treasure_open" class="chest locked">
            <img src="/images/chest-closed.webp" alt="Baú do Tesouro">
        </div>

    </div>

    <script>
        const mission1Completed =
            @json($mission1Completed);

        const mission2Completed =
            @json($mission2Completed);
    </script>

    <script>
        const mission1 = document.getElementById('mission1');
        const mission2 = document.getElementById('mission2');
        const chest = document.getElementById('treasureChest');


        if (mission2Completed) {

            unlockMission2();

            unlockTreasure();

        } else if (mission1Completed) {

            unlockMission2();

        }

        function unlockMission2() {

            mission1.classList.remove('pulse');
            mission1.classList.add('completed');

            mission2.classList.remove('locked');
            mission2.classList.remove('locked-disabled');
            mission2.classList.add('pulse');

            document.querySelectorAll('#x2 .treasure-x').forEach(line => {
                line.classList.remove('x-locked');
            });
        }

        function unlockTreasure() {

            mission2.classList.remove('pulse');
            mission2.classList.add('completed');

            chest.classList.remove('locked');
            chest.classList.add('pulse');

            chest.innerHTML = `
        <img src="/images/chest-closed.webp"
             alt="Baú do Tesouro">
    `;

            chest.addEventListener('click', () => {
                window.location.href = '/tesouro';
            });
        }
    </script>

</body>

</html>
