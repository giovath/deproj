<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recompensas Disponíveis</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">

    <div class="max-w-md mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="text-center mb-5">
            <div class="text-4xl mb-2">🎁</div>
            <h1 class="text-lg font-semibold">
                Recompensas Disponíveis Agora
            </h1>
            <p class="text-xs text-zinc-400 mt-1">
                Escolha uma opção abaixo e veja o que está disponível
            </p>
        </div>

        <!-- EXPLICAÇÃO -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 mb-5 text-xs text-zinc-300 leading-relaxed">
            Algumas plataformas liberam recompensas para novos usuários através de tarefas,
            jogos e interações digitais. As oportunidades podem variar ao longo do dia.
        </div>

        <!-- CTA PRINCIPAL (TOP OFFER) -->
        <div class="mb-5">
            <a href="SEU_MELHOR_LINK_AQUI"
                class="block w-full text-center py-3 rounded-xl
                bg-emerald-400 text-zinc-900 font-semibold
                hover:scale-[1.02] hover:bg-emerald-300
                transition-all duration-200">
                🔥 Acessar Melhor Oportunidade
            </a>
        </div>

        <!-- CARDS (ESTILO HOME) -->
        <div class="relative">

            <div
                class="
                flex md:grid md:grid-cols-3
                gap-3
                overflow-x-auto md:overflow-visible
                pb-2
                no-scrollbar
                scroll-smooth
            ">

                <!-- CARD 1 -->
                <div
                    class="
                    min-w-[75%] md:min-w-0
                    bg-zinc-900 border border-zinc-800
                    rounded-xl p-4
                    flex flex-col justify-between
                    hover:scale-[1.04]
                    hover:border-emerald-400
                    transition-all duration-200
                ">
                    <div>
                        <div class="text-[11px] text-emerald-400 mb-1 font-semibold">
                            💰 Tarefas
                        </div>

                        <div class="text-xs font-medium mb-4 leading-tight">
                            Complete tarefas simples e desbloqueie recompensas direto do celular
                        </div>
                    </div>

                    <a href="LINK_TAREFAS"
                        class="text-xs px-3 py-2 rounded-lg
                        bg-emerald-400 text-zinc-900 font-semibold
                        w-full text-center
                        hover:scale-105 hover:bg-emerald-300
                        transition-all duration-200">
                        Acessar agora
                    </a>
                </div>

                <!-- CARD 2 -->
                <div
                    class="
                    min-w-[75%] md:min-w-0
                    bg-zinc-900 border border-zinc-800
                    rounded-xl p-4
                    flex flex-col justify-between
                    hover:scale-[1.04]
                    hover:border-blue-400
                    transition-all duration-200
                ">
                    <div>
                        <div class="text-[11px] text-blue-400 mb-1 font-semibold">
                            🎮 Jogos
                        </div>

                        <div class="text-xs font-medium mb-4 leading-tight">
                            Jogue e participe de experiências interativas com recompensas disponíveis
                        </div>
                    </div>

                    <a href="LINK_JOGOS"
                        class="text-xs px-3 py-2 rounded-lg
                        bg-blue-400 text-zinc-900 font-semibold
                        w-full text-center
                        hover:scale-105 hover:bg-blue-300
                        transition-all duration-200">
                        Ver agora
                    </a>
                </div>

                <!-- CARD 3 -->
                <div
                    class="
                    min-w-[75%] md:min-w-0
                    bg-zinc-900 border border-zinc-800
                    rounded-xl p-4
                    flex flex-col justify-between
                    hover:scale-[1.04]
                    hover:border-amber-400
                    transition-all duration-200
                ">
                    <div>
                        <div class="text-[11px] text-amber-400 mb-1 font-semibold">
                            ⚡ Descobrir
                        </div>

                        <div class="text-xs font-medium mb-4 leading-tight">
                            Veja o que está disponível agora para o seu perfil
                        </div>
                    </div>

                    <a href="LINK_DESCOBERTA"
                        class="text-xs px-3 py-2 rounded-lg
                        bg-amber-400 text-zinc-900 font-semibold
                        w-full text-center
                        hover:scale-105 hover:bg-amber-300
                        transition-all duration-200">
                        Descobrir
                    </a>
                </div>

            </div>

            <!-- Fade esquerda -->
            <div
                class="pointer-events-none absolute left-0 top-0 h-full w-8 bg-gradient-to-r from-zinc-950 to-transparent">
            </div>

            <!-- Fade direita -->
            <div
                class="pointer-events-none absolute right-0 top-0 h-full w-8 bg-gradient-to-l from-zinc-950 to-transparent">
            </div>

        </div>

        <!-- CTA FINAL (REFORÇO) -->
        <div class="mt-6">
            <a href="SEU_MELHOR_LINK_AQUI"
                class="block w-full text-center py-3 rounded-xl
                bg-emerald-400 text-zinc-900 font-semibold
                hover:scale-[1.02] transition">
                Ver Melhor Opção Disponível
            </a>
        </div>

        <!-- DISCLAIMER -->
        <p class="text-[11px] text-zinc-500 text-center mt-4 leading-relaxed">
            As oportunidades podem variar conforme disponibilidade, região e perfil do usuário.
        </p>

    </div>

</body>

</html>
