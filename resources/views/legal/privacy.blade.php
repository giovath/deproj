<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Política de Privacidade – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-100">

    <div class="max-w-3xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold mb-6">Política de Privacidade</h1>

        <p class="text-sm text-gray-400 mb-8">
            Última atualização: {{ date('d/m/Y') }}
        </p>

        <p class="mb-6">
            Esta Política descreve como coletamos e utilizamos informações no {{ config('app.name') }}.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">1. Informações Coletadas</h2>
        <p>
            Ao utilizar o login com TikTok, coletamos apenas informações básicas do perfil, como:
        </p>
        <ul class="list-disc list-inside mt-2 text-gray-300">
            <li>ID do usuário</li>
            <li>Nome de exibição</li>
            <li>Foto de perfil</li>
        </ul>

        <h2 class="text-xl font-semibold mt-8 mb-2">2. Uso das Informações</h2>
        <p>
            As informações são utilizadas exclusivamente para autenticação, identificação e exibição em rankings.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">3. Compartilhamento</h2>
        <p>
            Não compartilhamos dados pessoais com terceiros.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">4. Segurança</h2>
        <p>
            Aplicamos medidas razoáveis para proteger os dados armazenados.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">5. Exclusão</h2>
        <p>
            O usuário pode solicitar a exclusão de sua conta a qualquer momento.
        </p>
    </div>

</body>

</html>
