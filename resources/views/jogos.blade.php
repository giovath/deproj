<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mapa de Expedições</title>

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

            margin-bottom: 30px;

        }


        .games {

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

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
    </style>

</head>


<body>


    <div class="container">


        <h1>
            ⚓ Escolha sua Expedição
        </h1>


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


                    <button>

                        🗺️ Explorar este território

                    </button>


                </div>
            @endforeach


        </div>


    </div>


</body>

</html>
