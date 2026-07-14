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


    <p>
        Jogo escolhido:
        {{ session('expedition_game') }}
    </p>


    <p>
        Início:
        {{ session('expedition_started_at') }}
    </p>


</body>

</html>
