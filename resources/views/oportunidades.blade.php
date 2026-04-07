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

        <!-- HEADER -->
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">📱</div>

            <h1 class="text-lg font-semibold">
                Oportunidades disponíveis hoje
            </h1>

            <p class="text-xs text-zinc-400 mt-1">
                Você pode começar agora usando apenas seu celular
            </p>
        </div>

        <!-- CONTEXTO (AO INVÉS DE "ESCOLHA") -->
        <div class="text-[11px] text-zinc-500 text-center mb-5">
            ⚡ Hoje você tem 2 formas reais de aproveitar online
        </div>

        <!-- CARD PRINCIPAL (CRÉDITO) -->
        <div class="bg-zinc-900 border-2 border-emerald-400 rounded-2xl p-5 shadow-lg mb-5">

            <div class="text-[10px] text-emerald-400 mb-2 font-medium">
                ⚡ Verificação disponível agora
            </div>

            <div class="text-[11px] text-emerald-400 mb-1 font-semibold">
                💸 Crédito pessoal
            </div>

            <div class="text-sm font-semibold mb-2">
                Veja agora se você tem um valor disponível no seu CPF
            </div>

            <div class="text-xs text-zinc-400 mb-3 leading-relaxed">
                Simulação rápida direto no celular. Sem burocracia e com resposta imediata.
            </div>

            <div class="text-[11px] text-zinc-500 mb-4">
                ✔ Processo 100% online<br>
                ✔ Sem compromisso<br>
                ✔ Não afeta seu score<br>
                ✔ Resultado na hora
            </div>

            <a href="https://susim.co/JLbf5NMLfEo1TSxiDyyLiQ==" target="_blank"
                class="block text-center py-3 rounded-xl bg-emerald-400 text-zinc-900 font-semibold text-sm hover:scale-105 hover:bg-emerald-300 transition">

                🔓 Ver meu valor disponível agora

            </a>

        </div>

        <!-- CONTEXTO PARA SEGUNDA OPÇÃO -->
        <div class="text-[11px] text-zinc-500 mb-3 text-center">
            💡 Prefere ganhar dinheiro online ao invés de crédito?
        </div>

        <!-- CARD SECUNDÁRIO (RENDA DIGITAL) -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg">

            <div class="text-[10px] text-amber-400 mb-2 font-medium">
                🔥 Alternativa disponível hoje
            </div>

            <div class="text-[11px] text-amber-400 mb-1 font-semibold">
                💻 Renda digital
            </div>

            <div class="text-sm font-semibold mb-2">
                Ganhe comissões divulgando produtos
            </div>

            <div class="text-xs text-zinc-400 mb-3 leading-relaxed">
                Método simples para começar hoje, sem precisar investir.
            </div>

            <div class="text-[11px] text-zinc-500 mb-4">
                Comissões de até 50% com pagamentos semanais.<br>
                Já utilizado por milhares de pessoas.
            </div>

            <a href="https://mypubly.com/joorcwh2"
                class="block text-center py-3 rounded-xl
                bg-amber-400 text-zinc-900 font-semibold text-sm
                hover:scale-105 hover:bg-amber-300 transition">

                💰 Ver como ganhar agora

            </a>

        </div>

        <div class="mt-6 text-center">
            <button onclick="sharePage()"
                class="w-full py-3 rounded-xl
        bg-gradient-to-r from-amber-400 to-yellow-300
        text-zinc-900 font-semibold
        animate-pulse">

                🚀 Compartilhe com alguém que precisa ver isso

                <div class="text-[10px] opacity-70 font-normal">
                    pode ajudar alguém hoje
                </div>
            </button>
        </div>

        <!-- OPÇÃO TERCIÁRIA -->
        <div class="mt-5 text-center">
            <a href="/recompensas" class="text-xs text-zinc-500 hover:text-amber-400 transition">
                ou começar com tarefas rápidas →
            </a>
        </div>

        <!-- VOLTAR -->
        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-zinc-600 hover:text-zinc-300 transition">
                ← Voltar
            </a>
        </div>

    </div>

    <script>
        function sharePage() {
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: 'Oportunidades disponíveis hoje',
                    text: 'Olha isso, pode te ajudar 👇',
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
