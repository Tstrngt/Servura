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
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in');
        }
    });
}, observerOptions);

// Observe elements that should animate on scroll
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});

// Initialize Alpine.js
Alpine.start();
