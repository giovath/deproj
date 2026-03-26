<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oportunidades | {{ config('app.name', 'premio.click') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">

    <div class="max-w-md mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">📱</div>

            <h1 class="text-lg font-semibold">
                Veja o que você pode explorar hoje
            </h1>

            <p class="text-xs text-zinc-400 mt-1">
                Aplicativos, tarefas e formas de usar o celular
            </p>
        </div>

        <!-- EXPLICAÇÃO -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 mb-5 text-xs text-zinc-300 leading-relaxed">
            Algumas opções podem oferecer benefícios, pontos ou comissões dependendo do uso.
            Explore e veja o que faz mais sentido para você.
        </div>

        <!-- LISTA DE OPORTUNIDADES -->
        <div class="space-y-3">

            <!-- RENDA DIGITAL (PRINCIPAL) -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">

                <div class="text-[11px] text-emerald-400 mb-1 font-semibold">
                    💻 Renda digital
                </div>

                <div class="text-xs mb-3 leading-tight">
                    Veja como algumas pessoas estão usando o celular para divulgar produtos e gerar comissões
                </div>

                <a href="https://mypubly.com/joorcwh2"
                    class="block text-center py-2 rounded-lg
                    bg-emerald-400 text-zinc-900 font-semibold text-xs
                    hover:scale-105 hover:bg-emerald-300 transition">
                    Ver como começar
                </a>

            </div>

            <!-- EXPERIÊNCIAS -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">

                <div class="text-[11px] text-blue-400 mb-1 font-semibold">
                    🎮 Experiências e tarefas
                </div>

                <div class="text-xs mb-3 leading-tight">
                    Explore aplicativos, jogos e interações disponíveis agora
                </div>

                <a href="/recompensas"
                    class="block text-center py-2 rounded-lg
                    bg-blue-400 text-zinc-900 font-semibold text-xs
                    hover:scale-105 hover:bg-blue-300 transition">
                    Ver opções
                </a>

            </div>

            <!-- RECOMPENSAS -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">

                <div class="text-[11px] text-amber-400 mb-1 font-semibold">
                    🎁 Recompensas disponíveis
                </div>

                <div class="text-xs mb-3 leading-tight">
                    Veja tarefas que podem liberar benefícios ou pontos
                </div>

                <a href="/recompensas"
                    class="block text-center py-2 rounded-lg
                    bg-amber-400 text-zinc-900 font-semibold text-xs
                    hover:scale-105 hover:bg-amber-300 transition">
                    Ver tarefas
                </a>

            </div>

        </div>

        <!-- VOLTAR -->
        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-zinc-500 hover:text-zinc-300 transition">
                ← Voltar
            </a>
        </div>

    </div>

</body>

</html>
