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

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    updateTheme();
});

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
