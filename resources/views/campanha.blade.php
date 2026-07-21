<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.analytics')
    
    <title>Click Premio - Campanha</title>

    <!-- Meta Pixel -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '1201306888367899');
        fbq('track', 'PageView');
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #e5ddd5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #075E54;
            color: white;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .contact-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .typing-status {
            font-size: 13px;
            color: #ddd;
            display: none;
        }

        .chat-container {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            max-width: 800px;
            margin: 0 auto;
        }

        .message {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 80%;
        }

        .bot {
            background-color: #fff;
        }

        .user {
            background-color: #dcf8c6;
            align-self: flex-end;
        }

        .buttons {
            display: none;
            padding: 15px;
        }

        .buttons button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 6px;
            background: #25D366;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .buttons button:hover {
            background: #128C7E;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="/images/vic1.png" class="contact-image">
        <div>
            Atendimento
            <div class="typing-status">Digitando...</div>
        </div>
    </div>

    <div class="chat-container"></div>

    <div class="buttons">
        <button onclick="optionSelected()">
            Quero participar da campanha
        </button>
    </div>

    <script>
        const chat = document.querySelector('.chat-container');
        const typing = document.querySelector('.typing-status');
        const buttons = document.querySelector('.buttons');

        const messages = [
            "Oi! 👋",
            "Estamos selecionando pessoas para participar de campanhas promocionais com grandes marcas.",
            "Algumas campanhas incluem produtos como smartphones e outros benefícios.",
            "Preciso fazer uma verificação rápida com você.",
            "É importante seguir até o final para validar sua participação.",
            "Posso liberar seu acesso agora?"
        ];

        let i = 0;

        function showTyping() {
            typing.style.display = 'block';

            setTimeout(() => {
                typing.style.display = 'none';
                addMessage(messages[i], 'bot');
                i++;

                if (i < messages.length) {
                    setTimeout(showTyping, 900);
                } else {
                    setTimeout(() => {
                        buttons.style.display = 'block';
                    }, 500);
                }
            }, 1200);
        }

        function addMessage(text, type) {
            const el = document.createElement('div');
            el.className = 'message ' + type;
            el.innerText = text;
            chat.appendChild(el);
            chat.scrollTop = chat.scrollHeight;
        }

        function optionSelected() {
            addMessage("Quero participar da campanha", 'user');
            buttons.style.display = 'none';

            setTimeout(() => {
                addMessage("Perfeito! Estamos verificando seu perfil...", 'bot');
            }, 800);

            setTimeout(() => {
                addMessage("Encontramos campanhas disponíveis para você.", 'bot');
            }, 2000);

            setTimeout(() => {
                addMessage("Vamos te direcionar agora para continuar.", 'bot');
            }, 3500);

            setTimeout(() => {
                fbq('track', 'Lead');

                // 🔥 LINK FINAL (FUN BOXES)
                window.location.href = "https://ldl1.com/link?z=9201663";

            }, 5000);
        }

        setTimeout(showTyping, 800);
    </script>

</body>

</html>
