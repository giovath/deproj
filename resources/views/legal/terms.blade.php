<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Termos de Uso – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-100">

    <div class="max-w-3xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold mb-6">Termos de Uso</h1>

        <p class="text-sm text-gray-400 mb-8">
            Última atualização: {{ date('d/m/Y') }}
        </p>

        <p class="mb-6">
            O {{ config('app.name') }} é uma plataforma web de jogos casuais onde usuários participam de partidas e
            rankings.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">1. Aceitação</h2>
        <p>
            Ao acessar ou utilizar a plataforma, você concorda com estes Termos de Uso.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">2. Autenticação</h2>
        <p>
            O acesso pode ocorrer por meio do login com TikTok, utilizando apenas informações básicas de perfil.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">3. Uso da Plataforma</h2>
        <p>
            É proibido explorar falhas, manipular rankings ou utilizar a plataforma de forma indevida.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">4. Disponibilidade</h2>
        <p>
            O serviço pode ser alterado ou interrompido a qualquer momento.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">5. Limitação de Responsabilidade</h2>
        <p>
            A plataforma é fornecida "como está", sem garantias explícitas.
        </p>
    </div>

</body>

</html>
