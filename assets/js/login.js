(function () {
    const usernameEl = document.getElementById('username');
    const passwordEl = document.getElementById('password');
    const loginForm = document.getElementById('loginForm');
    const btnLogin = document.getElementById('btnLogin');
    const togglePw = document.getElementById('togglePw');
    const eyeIcon = document.getElementById('eyeIcon');
    const forgotLink = document.getElementById('forgotLink');

    function clearErrors() {
        usernameEl.classList.remove('error');
        passwordEl.classList.remove('error');
        document.getElementById('errUsername').classList.remove('show');
        document.getElementById('errPassword').classList.remove('show');
        document.getElementById('errUsername').innerHTML =
            '<span class="material-icons">error_outline</span>Username wajib diisi';
        document.getElementById('errPassword').innerHTML =
            '<span class="material-icons">error_outline</span>Password wajib diisi';
    }

    function shakeEl(el) {
        const wrap = el.closest('.input-wrap');
        if (!wrap) return;
        wrap.classList.add('shake');
        setTimeout(function () {
            wrap.classList.remove('shake');
        }, 400);
    }

    if (togglePw) {
        togglePw.addEventListener('click', function () {
            passwordEl.type = passwordEl.type === 'password' ? 'text' : 'password';
            eyeIcon.textContent = passwordEl.type === 'password' ? 'visibility' : 'visibility_off';
        });
    }

    if (usernameEl) {
        usernameEl.addEventListener('input', function () {
            this.value = this.value.replace(/\s+/g, '');
            this.classList.remove('error');
            document.getElementById('errUsername').classList.remove('show');
        });
    }

    if (passwordEl) {
        passwordEl.addEventListener('input', function () {
            this.classList.remove('error');
            document.getElementById('errPassword').classList.remove('show');
        });
    }

    if (forgotLink) {
        forgotLink.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Lupa Password?',
                html: '<p style="font-size:14px;color:#6B4C35;line-height:1.6">Silakan hubungi administrator sistem untuk reset password.</p>',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#5C3317'
            });
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            clearErrors();

            let valid = true;
            const username = usernameEl.value.trim();
            const password = passwordEl.value.trim();

            if (!username) {
                e.preventDefault();
                usernameEl.classList.add('error');
                document.getElementById('errUsername').classList.add('show');
                shakeEl(usernameEl);
                valid = false;
            } else if (!/^[a-zA-Z0-9_-]+$/.test(username)) {
                e.preventDefault();
                usernameEl.classList.add('error');
                document.getElementById('errUsername').innerHTML =
                    '<span class="material-icons">error_outline</span>Username hanya boleh huruf, angka, underscore, dan dash';
                document.getElementById('errUsername').classList.add('show');
                shakeEl(usernameEl);
                valid = false;
            }

            if (!password) {
                e.preventDefault();
                passwordEl.classList.add('error');
                document.getElementById('errPassword').classList.add('show');
                shakeEl(passwordEl);
                valid = false;
            }

            if (!valid) {
                return false;
            }

            btnLogin.classList.add('loading');
        });
    }
})();