<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Q2GDTV3FK2"></script>

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-Q2GDTV3FK2');

    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('[data-event]').forEach(element => {

            element.addEventListener('click', () => {

                gtag('event', element.dataset.event);

            });

        });

    });
</script>
