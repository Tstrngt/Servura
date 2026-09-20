import './bootstrap';

import Alpine from 'alpinejs';

// Alpine.js components
Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('accordion', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    hide() {
        this.open = false;
        document.body.style.overflow = 'auto';
    }
}));

// Form validation
Alpine.data('contactForm', () => ({
    submitting: false,
    success: false,
    errors: {},
    
    async submit(event) {
        this.submitting = true;
        this.errors = {};
        
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'text/html',
                }
            });
            
            if (response.ok) {
                this.success = true;
                event.target.reset();
                
                // Scroll to top to show success message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    this.success = false;
                }, 5000);
            } else {
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract validation errors
                const errorElements = doc.querySelectorAll('.form-error');
                errorElements.forEach(element => {
                    const inputName = element.previousElementSibling?.getAttribute('name');
                    if (inputName) {
                        this.errors[inputName] = element.textContent;
                    }
                });
            }
        } catch (error) {
            console.error('Form submission error:', error);
        } finally {
            this.submitting = false;
        }
    }
}));

// Smooth scroll for anchor links
document.addEventListener('alpine:init', () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -80px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements that should animate on scroll
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});

// Glass navbar: switch between light/dark theme depending on the section
// currently sitting behind it, so the "Servura" logo and links stay readable.
document.addEventListener('DOMContentLoaded', () => {
    const nav = document.querySelector('[data-navbar]');
    if (!nav) return;

    const darkSections = Array.from(document.querySelectorAll('[data-navbar-theme="dark"]'));
    if (darkSections.length === 0) return;

    let ticking = false;

    function updateTheme() {
        ticking = false;
        // Point just below the navbar's own bottom edge — whichever section
        // currently covers that point decides the theme.
        const probeY = nav.getBoundingClientRect().bottom + 1;
        const isDark = darkSections.some((section) => {
            const rect = section.getBoundingClientRect();
            return rect.top <= probeY && rect.bottom >= probeY;
        });
        nav.classList.toggle('is-dark', isDark);
    }

    function onScroll() {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(updateTheme);
    }

    // The initial theme (scrollY 0) is already rendered server-side in Blade
    // (see layouts/app.blade.php) to avoid any race at load. From here on,
    // JS only needs to react to actual scrolling — it should never overwrite
    // that initial state on its own.
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
});

const serviceIcons = {
    sparkles: '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.091-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.091L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.091 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.091ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>',
    code: '<path stroke-linecap="round" stroke-linejoin="round" d="m17.25 6.75 4.5 4.5-4.5 4.5m-10.5 0-4.5-4.5 4.5-4.5m7.5-3-4.5 15"/>',
    device: '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>',
    search: '<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z"/>',
    server: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-7.5-6h12a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v6A2.25 2.25 0 0 0 6 14.25Z"/>',
    support: '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636A9 9 0 1 1 5.636 18.364 9 9 0 0 1 18.364 5.636ZM8.818 8.818l-3.182-3.182m9.546 3.182 3.182-3.182m-3.182 9.546 3.182 3.182m-9.546-3.182-3.182 3.182M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
    shield: '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>',
    chart: '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Zm6.75-4.5c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625Zm6.75-4.5C16.5 3.504 17.004 3 17.625 3h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>',
    globe: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9Zm0-18a9.004 9.004 0 0 1 7.843 4.582M12 3a9.004 9.004 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.919 17.919 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>',
    bolt: '<path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/>'
};

window.serviceIconSvg = (icon) => serviceIcons[icon] || serviceIcons.sparkles;

Alpine.data('popupEditor', (initial = {}) => ({
    icons: [
        ['sparkles', 'Sprankel'], ['code', 'Code'], ['device', 'Mobiel'], ['search', 'Zoeken'], ['server', 'Server'],
        ['support', 'Support'], ['shield', 'Beveiliging'], ['chart', 'Groei'], ['globe', 'Wereldwijd'], ['bolt', 'Snelheid']
    ],
    badges: (initial.badges || []).map((badge, index) => typeof badge === 'string'
        ? { text: badge, icon: ['sparkles', 'code', 'shield'][index] || 'sparkles' }
        : { text: badge.text || '', icon: badge.icon || 'sparkles' }),
    details: (initial.details || []).map((detail, index) => ({
        title: detail.title || '',
        description: detail.description || '',
        icon: detail.icon || ['sparkles', 'device', 'code', 'search', 'server', 'support'][index] || 'sparkles'
    })),
    iconSvg(icon) {
        return window.serviceIconSvg(icon);
    },
    addBadge() {
        this.badges.push({ text: '', icon: 'sparkles' });
    },
    removeBadge(index) {
        this.badges.splice(index, 1);
    },
    addDetail() {
        this.details.push({ title: '', description: '', icon: 'sparkles' });
    },
    removeDetail(index) {
        this.details.splice(index, 1);
    }
}));

Alpine.data('invoiceForm', () => ({
    lines: [{ description: '', quantity: 1, unit_price: 0 }],
    addLine() {
        this.lines.push({ description: '', quantity: 1, unit_price: 0 });
    },
    removeLine(index) {
        this.lines.splice(index, 1);
    },
    subtotal() {
        return this.lines.reduce((sum, l) => sum + (l.quantity * l.unit_price), 0);
    },
    vat() {
        return this.subtotal() * 0.21;
    },
    total() {
        return this.subtotal() + this.vat();
    }
}));

// Initialize Alpine.js
Alpine.start();
