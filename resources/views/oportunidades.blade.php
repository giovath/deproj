<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oportunidades | {{ config('app.name', 'premio.click') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/bg.css">
    <script src="/js/bg.js"></script>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">

    <div id="emojiBg" class="emoji-bg"></div>
    <div class="max-w-md mx-auto px-4 py-6">

        <div class="text-center mb-6">
            <div class="text-4xl mb-2">📱</div>

            <h1 class="text-lg font-semibold">
                Oportunidades disponíveis hoje
            </h1>

            <p class="text-xs text-zinc-400 mt-1">
                Escolha como deseja começar
            </p>
        </div>

        <!-- CARD CRÉDITO -->
        <a href="/credito" class="block">
            <div
                class="bg-zinc-900 border-2 border-emerald-400 rounded-2xl p-5 shadow-lg mb-5 hover:scale-[1.02] transition">

                <div class="text-[11px] text-emerald-400 mb-1 font-semibold">
                    💸 Crédito pessoal
                </div>

                <div class="text-sm font-semibold mb-2">
                    Comparar opções de crédito
                </div>

                <div class="text-xs text-zinc-400">
                    Veja ofertas disponíveis de acordo com seu perfil
                </div>

            </div>
        </a>

        <!-- CARD RENDA -->
        <a href="/renda" class="block">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg hover:scale-[1.02] transition">

                <div class="text-[11px] text-amber-400 mb-1 font-semibold">
                    💻 Renda online
                </div>

                <div class="text-sm font-semibold mb-2">
                    Ver formas de ganhar dinheiro online
                </div>

                <div class="text-xs text-zinc-400">
                    Descubra oportunidades digitais disponíveis
                </div>

            </div>
        </a>

    </div>
</body>

</html>
