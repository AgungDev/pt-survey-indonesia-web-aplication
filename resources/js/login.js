document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const toggle = document.querySelector('.password-toggle');

    if (!passwordInput || !toggle) {
        return;
    }

    toggle.addEventListener('click', function (event) {
        event.preventDefault();
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        const icon = toggle.querySelector('i');

        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    });
});
