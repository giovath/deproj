<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $gameTitle }}</title>

    <style>
        body {

            margin: 0;

            background: #111;

            color: white;

            font-family: Arial;

        }


        .header {

            padding: 20px;

            text-align: center;

        }


        iframe {

            width: 100%;

            height: 80vh;

            border: none;

        }
    </style>

</head>


<body>


    <div class="header">

        <h1>
            🎮 {{ $gameTitle }}
        </h1>


        <p>
            Modo Casual - sem recompensa
        </p>


    </div>


    <iframe src="{{ $gameUrl }}" allowfullscreen></iframe>


</body>

</html>
