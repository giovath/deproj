document.addEventListener('DOMContentLoaded', () => {

    const todayKey = new Date().toISOString().slice(0, 10);

    document.querySelectorAll('[data-offer-url]').forEach(container => {

        const btn = container.querySelector('.chest-btn');
        const img = container.querySelector('.chest-img');

        const offerUrl = container.dataset.offerUrl;
        const rewardUrl = container.dataset.rewardUrl;
        const maxReward = container.dataset.maxReward;

        const storageKey = 'chest_opened_' + todayKey;
        let opened = localStorage.getItem(storageKey) === '1';

        function applyState() {
            if (opened) {
                img.src = "/images/chest-open.png";
                img.classList.remove('chest-pulse');
                img.classList.add('chest-opened');
                btn.disabled = true;
            }
        }

        function showModal(html) {
            const modal = document.getElementById('genericModal');
            const content = document.getElementById('genericModalContent');

            content.innerHTML = html;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        btn.addEventListener('click', () => {

            if (opened || btn.disabled) return;;

            img.src = "/images/chest-open.png";
            img.classList.remove('chest-pulse');
            img.classList.add('chest-opened');

            btn.disabled = true;

            opened = true;
            localStorage.setItem(storageKey, '1');

            setTimeout(() => {

                showModal(`
                    <div class="text-center">
                        <div class="text-4xl mb-3">🎉</div>

                        <h3 class="text-sm font-semibold mb-2">
                            Recompensa desbloqueada!
                        </h3>

                        <p class="text-xs text-zinc-400 mb-4">
                            Você já tem <span class="text-amber-400 font-medium">até ${maxReward} moedas</span>.
                        </p>

                        <button class="unlock-btn w-full px-4 py-3 rounded-xl bg-amber-400 text-zinc-900 font-semibold animate-pulse">
                            ⚡ Liberar minhas moedas
                        </button>
                    </div>
                `);

                setTimeout(() => {
                    const unlockBtn = container.querySelector('.unlock-btn') || document.querySelector('.unlock-btn');
                    if (!unlockBtn) return;

                    unlockBtn.onclick = () => {

                        window.open(offerUrl, "_blank");

                        setTimeout(() => {
                            showModal(`
                <div class="text-center">
                    <div class="text-4xl mb-3">🎉</div>

                    <h3 class="text-sm font-semibold mb-2">
                        Recompensa desbloqueada!
                    </h3>

                    <a href="${rewardUrl}" target="_blank"
                       class="w-full inline-flex justify-center px-4 py-3 rounded-xl bg-amber-400 text-zinc-900 font-semibold">
                       🎁 Resgatar recompensa
                    </a>
                </div>
            `);
                        }, 1200);

                    };

                }, 50);

            }, 800);

        });

        applyState();
    });

});