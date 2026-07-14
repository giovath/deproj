<h1>Jogos</h1>

@foreach ($games as $game)
    <div>
        <img src="{{ $game->cover }}" width="200">

        <h2>
            {{ $game->title }}
        </h2>

        <p>
            {{ $game->category }}
        </p>
    </div>
@endforeach
