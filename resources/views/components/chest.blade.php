@props([
    'offerUrl' => '#',
    'rewardUrl' => '#',
    'maxReward' => 500,
])

<div class="flex flex-col items-center" data-offer-url="{{ $offerUrl }}" data-reward-url="{{ $rewardUrl }}"
    data-max-reward="{{ $maxReward }}">

    <button class="chest-btn p-0 bg-transparent hover:opacity-80 transition cursor-pointer">
        <img src="{{ asset('images/chest-closed.png') }}"
            class="chest-img w-56 h-56 chest-pulse transition-transform duration-300 hover:scale-105">
    </button>

    <div class="text-center mb-2">
        <div class="text-xs text-amber-400 font-semibold">
            🎁 Baú de recompensa diária
        </div>
        <div class="text-[10px] text-zinc-400">
            Disponível agora
        </div>
    </div>

</div>
