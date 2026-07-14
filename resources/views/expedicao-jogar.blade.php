<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Expedição</title>

</head>


<body>


    <h1>
        ⚓ Expedição iniciada
    </h1>


    <p>
        Sua aventura começou.
    </p>


    <h1>
        ⚓ Expedição em andamento
    </h1>


    <h2>
        {{ $game->title }}
    </h2>


    <iframe src="{{ $game->playUrl }}" width="100%" height="600" frameborder="0">
    </iframe>


    <p>
        Início:
        {{ session('expedition_started_at') }}
    </p>


</body>

</html>
