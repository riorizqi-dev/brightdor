import './bootstrap';

// Password visibility toggle (icon mata) untuk semua input password frontend.
function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        if (button.dataset.passwordBound === 'true') {
            return;
        }
        button.dataset.passwordBound = 'true';

        const field = button.closest('.bd-password-field');
        const input = field?.querySelector('[data-password-target]');
        const eye = button.querySelector('[data-password-icon-eye]');
        const eyeOff = button.querySelector('[data-password-icon-eye-off]');

        if (!input) {
            return;
        }

        button.addEventListener('click', () => {
            const revealed = input.type === 'text';
            input.type = revealed ? 'password' : 'text';
            eye?.classList.toggle('hidden', !revealed);
            eyeOff?.classList.toggle('hidden', revealed);
            button.setAttribute('aria-label', revealed ? 'Tampilkan password' : 'Sembunyikan password');
        });
    });
}

// Micro-animations: Scroll Reveal via Intersection Observer
function initScrollAnimations() {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
            el.classList.add('is-revealed');
        });
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                obs.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -40px 0px',
        threshold: 0.08,
    });

    document.querySelectorAll('.reveal-on-scroll:not(.is-revealed)').forEach((el) => {
        // If element is already in viewport on initial load, reveal it
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            el.classList.add('is-revealed');
        } else {
            observer.observe(el);
        }
    });
}

function initApp() {
    initPasswordToggles();
    initScrollAnimations();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}

document.addEventListener('livewire:navigated', initApp);

