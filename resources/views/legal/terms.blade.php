<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Termos de Uso – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-100">

    @include('partials.header')

    <div class="max-w-3xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold mb-6">Termos de Uso</h1>

        <p class="text-sm text-gray-400 mb-8">
            Última atualização: {{ date('d/m/Y') }}
        </p>

        <p class="mb-4">
            O <strong>{{ config('app.name') }}</strong> é uma plataforma web de jogos casuais
            onde usuários participam de partidas e rankings.
        </p>

        <p class="mb-8 text-sm text-gray-400">
            Esta plataforma é operada de forma independente. Para contato:
            <a href="mailto:contato@premio.click" class="underline">
                contato@premio.click
            </a>
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">1. Aceitação</h2>
        <p>
            Ao acessar ou utilizar a plataforma, o usuário declara que leu,
            compreendeu e concorda integralmente com estes Termos de Uso.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">2. Autenticação</h2>
        <p>
            O acesso à plataforma pode ocorrer por meio do login com TikTok,
            utilizando apenas informações básicas de perfil, conforme permitido
            pela API oficial da plataforma.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">3. Uso da Plataforma</h2>
        <p>
            O usuário compromete-se a utilizar a plataforma de forma ética e legal.
            É proibido explorar falhas, manipular rankings, automatizar interações
            ou utilizar a plataforma de maneira indevida.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">4. Disponibilidade</h2>
        <p>
            A plataforma pode ser alterada, suspensa ou descontinuada a qualquer
            momento, sem aviso prévio.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">5. Limitação de Responsabilidade</h2>
        <p>
            A plataforma é fornecida “como está”, sem garantias de disponibilidade,
            desempenho ou adequação a um propósito específico.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">6. Contato</h2>
        <p>
            Para dúvidas ou solicitações relacionadas a estes Termos de Uso,
            entre em contato pelo e-mail:
            <a href="mailto:contato@premio.click" class="underline">
                contato@premio.click
            </a>
        </p>
    </div>

    @include('partials.footer')

</body>

</html>
