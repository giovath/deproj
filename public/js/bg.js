document.addEventListener('DOMContentLoaded', () => {

    function generateEmojiRow(direction = 'left', index = 0) {
        const emojis = ['🎮', '🏆', '📈', '🍀'];

        let content = '';
        const repeatCount = Math.ceil(window.innerWidth / 40) * 20;

        let lastEmojis = [];

        for (let i = 0; i < repeatCount; i++) {
            let emoji;

            do {
                emoji = emojis[Math.floor(Math.random() * emojis.length)];
            } while (lastEmojis.includes(emoji));

            content += emoji + ' ';

            lastEmojis.push(emoji);
            if (lastEmojis.length > 2) lastEmojis.shift();
        }

        const fullContent = content + content;

        const row = document.createElement('div');
        row.className = 'row';

        const track = document.createElement('div');
        track.className = `track ${direction === 'left' ? 'move-left' : 'move-right'}`;

        const baseSpeed = 360;
        const variation = (index % 3) * 20;
        const duration = baseSpeed + variation;

        track.style.animationDuration = `${duration}s`;
        track.innerHTML = fullContent;

        row.appendChild(track);

        return row;
    }

    function initEmojiBackground() {
        const bg = document.getElementById('emojiBg');
        if (!bg) return;

        const rows = 12;

        for (let i = 0; i < rows; i++) {
            const direction = i % 2 === 0 ? 'left' : 'right';
            const row = generateEmojiRow(direction, i);
            bg.appendChild(row);
        }
    }

    initEmojiBackground();
});