<div class="hero-preview-shell" data-hero-preview data-theme="restaurant">
    <div class="hero-preview-browser">
        <div class="hero-preview-chrome hero-preview-piece" data-build-piece="chrome">
            <span class="hero-preview-dot"></span><span class="hero-preview-dot"></span><span class="hero-preview-dot"></span>
            <div class="hero-preview-address">www.voorbeeld.nl</div>
        </div>

        <div class="hero-preview-site">
            <header class="hero-preview-nav hero-preview-piece" data-build-piece="nav">
                <span class="hero-preview-logo">Atelier</span>
                <span class="hero-preview-nav-link">Menu</span>
                <span class="hero-preview-nav-link">Over ons</span>
                <span class="hero-preview-nav-link">Contact</span>
            </header>

            <div class="hero-preview-content">
                <section class="hero-preview-main hero-preview-piece" data-build-piece="main">
                    <div class="hero-preview-copy">
                        <span class="hero-preview-kicker">Sinds 2012</span>
                        <h3 class="hero-preview-title" data-copy-title>Proef het moment</h3>
                        <p class="hero-preview-text" data-copy-text>Een plek voor smaak, aandacht en bijzondere verhalen.</p>
                        <span class="hero-preview-button" data-copy-button>Reserveer nu</span>
                    </div>
                    <div class="hero-preview-image" aria-hidden="true">
                        <span class="hero-preview-image-label" data-image-label>Fine dining</span>
                    </div>
                </section>

                <section class="hero-preview-detail hero-preview-piece" data-build-piece="detail">
                    <div class="hero-preview-detail-card hero-preview-detail-one">
                        <span class="hero-preview-detail-value" data-stat-one>4.9</span>
                        <span class="hero-preview-detail-label" data-stat-one-label>Gasten waarderen ons</span>
                    </div>
                    <div class="hero-preview-detail-card hero-preview-detail-two">
                        <span class="hero-preview-detail-value" data-stat-two>12</span>
                        <span class="hero-preview-detail-label" data-stat-two-label>Seizoensgerechten</span>
                    </div>
                    <div class="hero-preview-detail-card hero-preview-detail-three">
                        <span class="hero-preview-detail-value" data-stat-three>1</span>
                        <span class="hero-preview-detail-label" data-stat-three-label>Unieke ervaring</span>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="hero-preview-skeleton" aria-hidden="true">
        <span class="hero-preview-skeleton-line hero-preview-skeleton-one"></span>
        <span class="hero-preview-skeleton-line hero-preview-skeleton-two"></span>
        <span class="hero-preview-skeleton-block"></span>
    </div>
</div>

<div class="mt-5 flex flex-wrap justify-center gap-2" role="group" aria-label="Kies een voorbeeldbranche">
    <button type="button" class="hero-theme-chip is-active" data-hero-theme="restaurant" aria-pressed="true">Restaurant</button>
    <button type="button" class="hero-theme-chip" data-hero-theme="hairdresser" aria-pressed="false">Kapper</button>
    <button type="button" class="hero-theme-chip" data-hero-theme="construction" aria-pressed="false">Bouw</button>
</div>

<script>
(function () {
    const preview = document.querySelector('[data-hero-preview]');
    if (!preview) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const themes = {
        restaurant: { title: 'Proef het moment', text: 'Een plek voor smaak, aandacht en bijzondere verhalen.', button: 'Reserveer nu', image: 'Fine dining', one: '4.9', oneLabel: 'Gasten waarderen ons', two: '12', twoLabel: 'Seizoensgerechten', three: '1', threeLabel: 'Unieke ervaring' },
        hairdresser: { title: 'Jouw stijl, jouw moment', text: 'Persoonlijke aandacht voor haar dat precies bij je past.', button: 'Plan je afspraak', image: 'Studio style', one: '15', oneLabel: 'Jaar ervaring', two: '640', twoLabel: 'Tevreden klanten', three: '6', threeLabel: 'Stylisten' },
        construction: { title: 'Bouwen aan morgen', text: 'Vakkundig uitgevoerd, helder gepland en duurzaam gebouwd.', button: 'Vraag een offerte', image: 'Vakwerk', one: '98%', oneLabel: 'Op tijd opgeleverd', two: '24', twoLabel: 'Projecten per jaar', three: '1', threeLabel: 'Vast aanspreekpunt' }
    };

    const fillTheme = (theme) => {
        const content = themes[theme];
        preview.dataset.theme = theme;
        preview.querySelector('[data-copy-title]').textContent = content.title;
        preview.querySelector('[data-copy-text]').textContent = content.text;
        preview.querySelector('[data-copy-button]').textContent = content.button;
        preview.querySelector('[data-image-label]').textContent = content.image;
        preview.querySelector('[data-stat-one]').textContent = content.one;
        preview.querySelector('[data-stat-one-label]').textContent = content.oneLabel;
        preview.querySelector('[data-stat-two]').textContent = content.two;
        preview.querySelector('[data-stat-two-label]').textContent = content.twoLabel;
        preview.querySelector('[data-stat-three]').textContent = content.three;
        preview.querySelector('[data-stat-three-label]').textContent = content.threeLabel;
    };

    const playBuild = () => {
        if (reduceMotion) return;
        preview.classList.remove('is-built');
        preview.classList.add('is-assembling');
        requestAnimationFrame(() => requestAnimationFrame(() => preview.classList.add('is-built')));
    };

    if (!reduceMotion) playBuild();

    document.querySelectorAll('[data-hero-theme]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const theme = chip.dataset.heroTheme;
            fillTheme(theme);
            document.querySelectorAll('[data-hero-theme]').forEach((button) => {
                const active = button === chip;
                button.classList.toggle('is-active', active);
                button.setAttribute('aria-pressed', String(active));
            });
            playBuild();
        });
    });
})();
</script>
