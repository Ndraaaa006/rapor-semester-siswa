document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm-message]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm-message');
            if (message && !window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-toggle-password');
            const input = targetSelector ? document.querySelector(targetSelector) : null;
            if (!input) {
                return;
            }

            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            button.textContent = isPassword ? 'Sembunyi' : 'Lihat';
        });
    });
});
