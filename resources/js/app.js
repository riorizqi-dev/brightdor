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

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordToggles);
} else {
    initPasswordToggles();
}
