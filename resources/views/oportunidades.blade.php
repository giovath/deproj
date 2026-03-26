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
                Escolha como quer começar hoje
            </h1>

            <p class="text-xs text-zinc-400 mt-1">
                Explore possibilidades disponíveis no seu celular
            </p>
        </div>

        <!-- OPÇÕES -->
        <div class="space-y-4">

            <!-- RENDA DIGITAL (HIGH VALUE) -->
            <div class="bg-zinc-900 border border-emerald-400 rounded-xl p-4">

                <div class="text-[11px] text-emerald-400 mb-1 font-semibold">
                    💻 Renda digital
                </div>

                <div class="text-xs mb-3 leading-tight">
                    Veja como divulgar produtos e gerar comissões usando apenas o celular
                </div>

                <a href="https://mypubly.com/joorcwh2"
                    class="block text-center py-3 rounded-lg
                    bg-emerald-400 text-zinc-900 font-semibold text-sm
                    hover:scale-105 hover:bg-emerald-300 transition">
                    Ver como começar
                </a>

            </div>

            <!-- RECOMPENSAS -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">

                <div class="text-[11px] text-amber-400 mb-1 font-semibold">
                    🎁 Tarefas e recompensas
                </div>

                <div class="text-xs mb-3 leading-tight">
                    Complete tarefas, teste apps e explore opções disponíveis hoje
                </div>

                <a href="/recompensas"
                    class="block text-center py-3 rounded-lg
                    bg-amber-400 text-zinc-900 font-semibold text-sm
                    hover:scale-105 hover:bg-amber-300 transition">
                    Ver tarefas disponíveis
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
