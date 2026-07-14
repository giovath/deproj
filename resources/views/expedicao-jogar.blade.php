<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Expedição Premiada</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            min-height: 100vh;

            background:
                linear-gradient(rgba(0, 0, 0, .75),
                    rgba(0, 0, 0, .85)),
                url('/images/treasure-room-bg.jpg');


            background-size: cover;

            background-position: center;

            font-family: Arial, sans-serif;

            color: #f5deb3;

            padding: 20px;

        }



        .container {

            width: 100%;

            max-width: 1000px;

            margin: auto;

        }



        h1 {

            text-align: center;

            color: #f0c36a;

            margin-bottom: 25px;

        }



        .panel {

            background:

                rgba(20, 12, 5, .9);


            border:

                1px solid rgba(212, 170, 74, .3);


            border-radius: 20px;


            padding: 20px;


            margin-bottom: 20px;

            text-align: center;

        }



        .panel h2 {

            color: #f0c36a;

            margin-bottom: 10px;

        }



        .status {

            display: flex;

            justify-content: center;

            gap: 20px;

            flex-wrap: wrap;

        }



        .status-box {

            background:

                rgba(255, 255, 255, .05);


            border-radius: 14px;


            padding: 15px 25px;

        }



        .status-box strong {

            display: block;

            font-size: 1.5rem;

            color: #f0c36a;

            margin-top: 5px;

        }



        .game-container {

            width: 100%;

            overflow: hidden;

            border-radius: 20px;

        }



        iframe {

            width: 100%;

            height: 700px;

            border: none;

            border-radius: 20px;

            background: black;

        }



        button {

            margin-top: 20px;

            width: 100%;

            padding: 15px;


            border:

                none;


            border-radius: 15px;


            background:

                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);


            color: white;

            font-weight: bold;

            cursor: pointer;

        }

        .modal {

            position: fixed;

            inset: 0;

            background:
                rgba(0, 0, 0, .75);

            display: none;

            justify-content: center;

            align-items: center;

            z-index: 999;

        }


        .modal.active {

            display: flex;

        }



        .modal-content {

            width: 90%;

            max-width: 380px;

            background:
                rgba(20, 12, 5, .98);

            border:

                2px solid rgba(212, 170, 74, .5);


            border-radius: 25px;

            padding: 30px;

            text-align: center;

            color: #f5deb3;

            animation: aparecer .3s ease;

        }



        .modal-content h2 {

            color: #f0c36a;

            margin-bottom: 15px;

        }



        .modal-icon {

            font-size: 60px;

            margin-bottom: 15px;

        }



        .modal-content strong {

            display: block;

            font-size: 3rem;

            color: #f0c36a;

            margin: 10px;

        }



        @keyframes aparecer {

            from {

                transform: scale(.7);

                opacity: 0;

            }

            to {

                transform: scale(1);

                opacity: 1;

            }

        }
    </style>


</head>


<body>


    <div class="container">


        <h1>
            ⚓ Expedição Premiada
        </h1>



        <div class="panel">


            <h2>
                {{ $gameTitle }}
            </h2>


            <p>
                Sua jornada começou.
            </p>


        </div>



        <div class="panel status">


            <div class="status-box">

                ⏱ Tempo restante

                <strong id="timer">

                    Carregando...

                </strong>


            </div>



            <div class="status-box">

                🏺 Relíquias encontradas

                <strong id="relics">

                    0

                </strong>


            </div>


        </div>




        <div class="panel game-container">


            <iframe src="{{ $gameUrl }}" allowfullscreen>

            </iframe>


        </div>



        <div class="panel">


            <p>

                Continue jogando para completar sua expedição.

                Ao final, suas conquistas serão enviadas ao Porto.

            </p>


            <button id="finishButton" disabled>

                ⏳ Expedição em andamento

            </button>


        </div>



    </div>

    <div id="rewardModal" class="modal">

        <div class="modal-content">

            <div class="modal-icon">
                🏺
            </div>

            <h2>
                Expedição concluída!
            </h2>

            <p>
                Você encontrou:
            </p>

            <strong id="rewardAmount">
                0
            </strong>

            <p>
                Relíquias
            </p>


            <button id="returnPort">
                ⚓ Retornar ao Porto
            </button>

        </div>

    </div>


    <script>
        let startedAt =
            new Date("{{ $startedAt->toIso8601String() }}").getTime();


        let remaining =
            {{ $duration }} -
            Math.floor(
                (Date.now() - startedAt) / 1000
            );


        if (remaining < 0) {
            remaining = 0;
        }


        const timer =
            document.getElementById('timer');


        const finishButton =
            document.getElementById('finishButton');


        const relics =
            document.getElementById('relics');

        const modal =
            document.getElementById('rewardModal');

        const rewardAmount =
            document.getElementById('rewardAmount');

        const returnPort =
            document.getElementById('returnPort');


        returnPort.addEventListener('click', () => {

            window.location.href = '/porto';

        });



        const countdown = setInterval(() => {

            let minutes =
                Math.floor(remaining / 60);

            let seconds =
                remaining % 60;


            timer.innerText =
                minutes + ":" +
                seconds.toString().padStart(2, '0');


            if (remaining <= 0) {

                clearInterval(countdown);

                finishButton.disabled = false;

                finishButton.innerText =
                    '🏆 Resgatar Relíquias';

                return;
            }


            remaining--;

        }, 1000);





        finishButton.addEventListener('click', () => {


            fetch('/expedicao/finalizar', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }

                })

                .then(async response => {

                    const text = await response.text();

                    console.log('Resposta do servidor:', text);

                    const data = JSON.parse(text);

                    return data;

                })

                .then(data => {


                    if (!data.success) {

                        alert(data.message);

                        return;
                    }



                    relics.innerText =
                        data.relics;


                    rewardAmount.innerText =
                        data.relics;


                    modal.classList.add('active');


                    finishButton.innerText =
                        '✨ Expedição concluída';


                });

        });
    </script>

</body>

</html>
