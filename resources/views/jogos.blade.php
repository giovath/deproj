<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')

    <title>Catálogo de Jogos</title>


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

            font-family: Arial, sans-serif;

            padding: 20px;

            color: #f5deb3;

        }



        .container {

            max-width: 900px;

            margin: auto;

        }



        h1 {

            text-align: center;

            color: #f0c36a;

            margin-bottom: 25px;

        }



        .top-panel {


            background:
                rgba(20, 12, 5, .9);


            border:
                1px solid rgba(212, 170, 74, .3);


            border-radius: 20px;


            padding: 20px;


            margin-bottom: 25px;


            text-align: center;

        }



        .top-panel strong {

            color: #f0c36a;

        }



        .stats {

            display: flex;

            justify-content: center;

            gap: 20px;

            flex-wrap: wrap;

            margin-bottom: 15px;

        }



        .stat {


            background:
                rgba(255, 255, 255, .05);


            padding: 12px 20px;


            border-radius: 14px;


        }



        button {

            margin-top: 15px;

            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 14px;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            color: white;

            font-weight: bold;

            cursor: pointer;

        }



        .games {

            display: grid;

            grid-template-columns:
                repeat(auto-fit,
                    minmax(220px, 1fr));

            gap: 20px;

        }



        .card {

            background:
                rgba(20, 12, 5, .9);


            border-radius: 20px;


            padding: 20px;


            text-align: center;


            border:
                1px solid rgba(212, 170, 74, .3);

        }



        .card img {

            width: 100%;

            border-radius: 14px;

        }



        .card h2 {

            margin: 15px 0;

            color: #f0c36a;

        }



        .card p {

            opacity: .8;

            min-height: 40px;

        }



        .free {

            background:
                linear-gradient(180deg,
                    #4b82d6,
                    #2757b8);

        }
    </style>


</head>


<body>


    <div class="container">



        <h1>
            🎮 Catálogo de Jogos
        </h1>



        <div class="top-panel">


            <div class="stats">


                <div class="stat">

                    💰 Moedas

                    <br>

                    <strong id="coins">

                        {{ session('coins', 0) }}

                    </strong>


                </div>



                <div class="stat">

                    ⚓ Participações

                    <br>

                    <strong id="participations">

                        {{ session('participations', 0) }}

                    </strong>


                </div>

                <div class="stat">

                    🏺 Relíquias

                    <br>

                    <strong id="relics">

                        {{ session('expedition_relics', 0) }}

                    </strong>

                </div>


            </div>



            <p>

                Cada participação permite iniciar uma Expedição Premiada e conquistar Relíquias.

            </p>



            <button id="buyParticipationButton">

                ⚓ Comprar Participação (100 moedas)

            </button>



        </div>





        <div class="games">


            @foreach ($games as $game)
                <div class="card">


                    <img src="{{ $game->cover }}">



                    <h2>
                        {{ $game->title }}
                    </h2>



                    <p>
                        {{ $game->category }}
                    </p>



                    <form method="GET" action="/jogo/{{ $game->id }}">


                        <button class="free" data-event="game_start">

                            🎮 Jogar Livremente

                        </button>


                    </form>




                    <form method="POST" action="/expedicao/iniciar/{{ $game->id }}">


                        @csrf


                        <button>

                            ⚓ Expedição Premiada

                        </button>


                    </form>



                </div>
            @endforeach



        </div>


    </div>



    <script>
        const buyButton =
            document.getElementById('buyParticipationButton');


        const participation =
            document.getElementById('participations');

        const coins =
            document.getElementById('coins');

        const relics =
            document.getElementById('relics');

        buyButton.addEventListener('click', () => {


            fetch('/jogos/comprar-participacao', {


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


                    participation.innerText =
                        data.participations;

                    if (data.relics !== undefined) {
                        relics.innerText = data.relics;
                    }


                    alert('Participação adquirida!');


                });


        });
    </script>



</body>

</html>
