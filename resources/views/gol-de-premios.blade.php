<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gol de Prêmios | Acesse Agora</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/bg.css">
    <script src="/js/bg.js"></script>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 flex items-center justify-center">

    <div id="emojiBg" class="emoji-bg"></div>

    <div class="max-w-md w-full px-5 py-8 text-center space-y-6">

        <!-- Ícone -->
        <div class="text-5xl">⚽</div>

        <!-- Headline -->
        <h1 class="text-xl font-bold leading-snug">
            Você pode ter um prêmio disponível agora
        </h1>

        <!-- Sub -->
        <p class="text-sm text-zinc-400">
            Faça seus chutes e veja quais recompensas foram liberadas hoje
        </p>

        <!-- Prova -->
        <div class="text-xs text-emerald-400">
            ✔ Acesso liberado hoje<br>
            ✔ Disponível no celular<br>
            ✔ Participação rápida
        </div>

        <!-- CTA PRINCIPAL -->
        <a href="https://www.tiktok.com/d/1/ZS98Ep3KjHPHc-42lMC/"
            class="block w-full py-4 rounded-2xl
       bg-emerald-400 text-zinc-900 font-bold text-lg
       hover:scale-105 transition">

            ⚽ Ver meus chutes disponíveis

        </a>

        <!-- Compartilhar -->
        <button onclick="sharePage()"
            class="w-full py-3 rounded-xl
        bg-zinc-800 text-zinc-300 text-sm
        hover:bg-zinc-700 transition">

            📲 Compartilhar com alguém

        </button>

        <!-- Urgência -->
        <div class="text-[11px] text-zinc-500">
            Disponível por tempo limitado hoje
        </div>

        <!-- Link secundário (discreto) -->
        <div class="text-xs text-zinc-600">
            ou <a href="/oportunidades" class="underline hover:text-zinc-300">
                ver outras oportunidades
            </a>
        </div>

    </div>

    <script>
        function sharePage() {
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: 'Gol de Prêmios',
                    text: 'Olha isso, pode ter recompensa liberada 👇',
                    url: url
                });
            } else {
                navigator.clipboard.writeText(url);
                alert('Link copiado! Compartilhe com alguém 😉');
            }
        }
    </script>

</body>

</html>
