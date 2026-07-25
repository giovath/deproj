<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')

    <title>{{ __('messages.navio_fortuna') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            min-height: 100dvh;

            background:
                linear-gradient(rgba(0, 0, 0, .55),
                    rgba(0, 0, 0, .78)),
                url('/images/treasure-ship-bg.jpg');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family: Arial, sans-serif;
        }

        .container {

            width: 100%;
            max-width: 430px;

            background: rgba(18, 10, 4, .88);

            border: 2px solid rgba(212, 170, 74, .22);

            border-radius: 26px;

            padding: 28px;

            backdrop-filter: blur(8px);

            color: #f5deb3;

            text-align: center;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, .45);
        }

        h1 {

            font-family: 'Cinzel', serif;

            color: #f0c36a;

            font-size: 2rem;

            margin-bottom: 14px;
        }

        .description {

            line-height: 1.65;

            opacity: .92;

            margin-bottom: 28px;
        }

        .crew-box {

            display: flex;
            justify-content: center;
            gap: 18px;

            margin-bottom: 28px;
        }

        .crew-slot {

            width: 120px;
            height: 120px;

            border-radius: 20px;

            border: 2px dashed rgba(255, 255, 255, .15);

            background: rgba(255, 255, 255, .05);

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            transition: .25s;
        }

        .crew-slot.locked {

            opacity: .45;
            filter: grayscale(1);
        }

        .crew-slot.active {

            border-color: #f0c36a;

            background: rgba(240, 195, 106, .12);

            box-shadow:
                0 0 18px rgba(240, 195, 106, .18);
        }

        .crew-icon {

            font-size: 2.2rem;

            margin-bottom: 10px;
        }

        .crew-label {

            font-size: .92rem;

            font-weight: bold;
        }

        .invite-box {

            background: rgba(255, 255, 255, .05);

            border-radius: 18px;

            padding: 18px;

            margin-bottom: 18px;

            border: 1px solid rgba(255, 255, 255, .06);
        }

        .invite-title {

            margin-bottom: 12px;

            font-size: .95rem;

            opacity: .9;
        }

        .invite-link {

            word-break: break-all;

            font-size: .88rem;

            opacity: .78;

            margin-bottom: 16px;

            line-height: 1.5;
        }

        .invite-button {

            width: 100%;

            border: none;

            border-radius: 14px;

            padding: 15px;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            color: #fff;

            font-weight: bold;

            font-size: 1rem;

            cursor: pointer;

            transition: .2s;
        }

        .invite-button:active {

            transform: scale(.98);
        }

        .status {

            margin-top: 18px;

            min-height: 48px;

            opacity: .92;

            line-height: 1.5;
        }

        #unlockButton {

            width: 100%;

            margin-top: 24px;

            border: none;

            border-radius: 16px;

            padding: 16px;

            background: #6e5a2d;

            color: #fff;

            font-weight: bold;

            font-size: 1rem;

            opacity: .5;

            cursor: not-allowed;

            transition: .25s;
        }

        #unlockButton.active {

            opacity: 1;

            cursor: pointer;

            background:
                linear-gradient(180deg,
                    #d6a84d,
                    #b98526);

            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }

        @media (min-width: 768px) {

            .crew-slot {

                width: 140px;
                height: 140px;
            }
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>⚓ {{ __('messages.navio_fortuna') }}</h1>

        <p class="description">

            {{ __('messages.navio_descricao') }}

        </p>

        <div class="crew-box">

            <!-- USUÁRIO -->
            <div id="slot1" class="crew-slot active">

                <div class="crew-icon">
                    🏴‍☠️
                </div>

                <div class="crew-label">
                    {{ __('messages.capitao_pronto') }}
                </div>

            </div>

            <!-- CONVIDADO -->
            <div id="slot2" class="crew-slot locked">

                <div class="crew-icon">
                    ⚓
                </div>

                <div class="crew-label">
                    {{ __('messages.aguardando_aliado') }}
                </div>

            </div>

        </div>

        <div class="invite-box">

            <div class="invite-title">

                {{ __('messages.chamado_tripulacao') }}

            </div>

            <div class="invite-link" id="inviteLink">

                {{ url('/ilha-da-fortuna?ref=' . $captain->ref_code) }}

            </div>

            <button id="copyButton" class="invite-button">

                {{ __('messages.copiar_convite') }}

            </button>

        </div>

        <p class="status" id="status">

            {{ __('messages.tripulacao_precisa_aliado') }}

        </p>

        <button id="unlockButton" disabled>

            {{ __('messages.chave_incompleta') }}

        </button>

    </div>

    <script>
        const referralCompleted =
            @json($referralCompleted);
    </script>

    <script>
        const slot2 =
            document.getElementById('slot2');

        const status =
            document.getElementById('status');

        const unlockButton =
            document.getElementById('unlockButton');

        const copyButton =
            document.getElementById('copyButton');

        const inviteLink =
            document.getElementById('inviteLink');

        // =========================
        // COPIAR LINK
        // =========================

        copyButton.addEventListener('click', async () => {

            try {

                await navigator.clipboard.writeText(
                    inviteLink.innerText
                );

                status.innerText =
                    @json(__('messages.convite_copiado'));

            } catch (error) {

                status.innerText =
                    @json(__('messages.erro_copiar_convite'));
            }

        });

        // =========================
        // ESTADO REAL DO BACKEND
        // =========================

        function activateReferralSuccess() {

            if (!unlockButton.disabled) {
                return;
            }

            slot2.classList.remove('locked');

            slot2.classList.add('active');

            slot2.querySelector('.crew-label').innerText =
                @json(__('messages.aliado_recrutado'));

            status.innerText =
                @json(__('messages.aliado_encontrou_fragmento'));

            unlockButton.disabled = false;

            unlockButton.classList.add('active');

            unlockButton.innerText =
                @json(__('messages.abrir_bau_tesouro'));
        }

        if (referralCompleted) {

            activateReferralSuccess();

        }

        let referralInterval = null;

        async function checkReferralStatus() {

            try {

                const response =
                    await fetch('/mission2/status');

                const data =
                    await response.json();

                if (!data.completed) {
                    return;
                }

                activateReferralSuccess();

                clearInterval(referralInterval);

            } catch (error) {

                console.log(error);

            }
        }

        if (!referralCompleted) {

            referralInterval = setInterval(() => {

                checkReferralStatus();

            }, 4000);

        }

        // =========================
        // FINALIZAÇÃO
        // =========================

        unlockButton.addEventListener('click', () => {

            if (unlockButton.disabled) {
                return;
            }

            unlockButton.disabled = true;

            unlockButton.innerText =
                @json(__('messages.abrindo_tesouro'));

            fetch('/mission2/complete', {

                method: 'POST',

                credentials: 'same-origin',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }

            }).then(() => {

                window.location.href = '/';

            });

        });
    </script>

</body>

</html>
