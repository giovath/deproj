<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Política de Privacidade – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-100">

    @include('partials.header')

    <div class="max-w-3xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold mb-6">Política de Privacidade</h1>

        <p class="text-sm text-gray-400 mb-8">
            Última atualização: {{ date('d/m/Y') }}
        </p>

        <p class="mb-4">
            Esta Política de Privacidade descreve como o
            <strong>{{ config('app.name') }}</strong> coleta, utiliza
            e protege as informações dos usuários.
        </p>

        <p class="mb-8 text-sm text-gray-400">
            Para dúvidas relacionadas à privacidade ou solicitações de dados,
            entre em contato pelo e-mail:
            <a href="mailto:contato@premio.click" class="underline">
                contato@premio.click
            </a>
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">1. Informações Coletadas</h2>
        <p>
            Ao utilizar o login com TikTok, coletamos apenas informações básicas
            do perfil do usuário, tais como:
        </p>
        <ul class="list-disc list-inside mt-2 text-gray-300">
            <li>ID do usuário</li>
            <li>Nome de exibição</li>
            <li>Foto de perfil</li>
        </ul>

        <h2 class="text-xl font-semibold mt-8 mb-2">2. Uso das Informações</h2>
        <p>
            As informações coletadas são utilizadas exclusivamente para fins
            de autenticação, identificação do usuário e exibição em rankings
            dentro da plataforma.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">3. Compartilhamento de Dados</h2>
        <p>
            Não compartilhamos dados pessoais dos usuários com terceiros.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">4. Segurança</h2>
        <p>
            Adotamos medidas técnicas e organizacionais razoáveis para proteger
            as informações armazenadas contra acesso não autorizado.
        </p>

        <h2 class="text-xl font-semibold mt-8 mb-2">5. Direitos do Usuário</h2>
        <p>
            O usuário pode solicitar a qualquer momento:
        </p>
        <ul class="list-disc list-inside mt-2 text-gray-300">
            <li>Acesso aos seus dados pessoais</li>
            <li>Correção de informações</li>
            <li>Exclusão de sua conta e dados</li>
        </ul>

        <h2 class="text-xl font-semibold mt-8 mb-2">6. Consentimento</h2>
        <p>
            Ao utilizar a plataforma, o usuário concorda com a coleta e o uso
            de suas informações conforme descrito nesta Política de Privacidade.
        </p>
    </div>
    @include('partials.footer')
</body>

</html>
