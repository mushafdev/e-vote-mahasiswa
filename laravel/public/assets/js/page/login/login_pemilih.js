var urlAuthenticate  = '';
var urlFaceVerify    = '';
var csrfToken        = '';
var cameraStream     = null;
var turnstileToken   = '';
var cloudflareActive = false;

function onTurnstileSuccess(token) {
    turnstileToken = token;
    document.getElementById('btn-login').disabled = false;
}

function togglePassword() {
    var input = document.getElementById('password');
    var icon  = document.querySelector('#show_hide_password .show-pass i');
    if (!input) { console.warn('password input not found'); return; }
    var newType = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', newType);
    input.classList.toggle('pw-dots', newType === 'password');
    if (icon) {
        if (newType === 'text') {
            icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash');
        }
    }
}

window.addEventListener('DOMContentLoaded', function () {

    var cfg = document.getElementById('login-config');
    if (cfg) {
        urlAuthenticate  = cfg.getAttribute('data-authenticate');
        urlFaceVerify    = cfg.getAttribute('data-face-verify');
        csrfToken        = cfg.getAttribute('data-csrf');
        cloudflareActive = cfg.getAttribute('data-cloudflare') === 'active';
    }

    // Enable semua tombol jika cloudflare tidak aktif
    if (!cloudflareActive) {
        document.querySelectorAll('button').forEach(function(b){ b.disabled = false; });
        setTimeout(function(){
            document.querySelectorAll('button').forEach(function(b){ b.disabled = false; });
        }, 500);
    }

    // Show/hide password — pasang langsung ke button DAN icon via native addEventListener
    var showPassBtn = document.querySelector('#show_hide_password .show-pass');
    if (showPassBtn) {
        showPassBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            togglePassword();
        }, true); // useCapture = true agar dapat event sebelum handler lain
    }

    // Block form submit — handle via tombol
    var formLogin = document.getElementById('form-login');
    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();
            doLogin();
        });
    }

    // Tombol masuk
    var btnLogin = document.getElementById('btn-login');
    if (btnLogin) {
        btnLogin.addEventListener('click', function(e) {
            e.preventDefault();
            doLogin();
        });
    }

    // Tombol kembali
    var btnBack = document.getElementById('btn-back');
    if (btnBack) {
        btnBack.addEventListener('click', function(e) {
            e.preventDefault();
            stopCamera();
            document.getElementById('step-face').style.display = 'none';
            document.getElementById('step-credentials').style.display = '';
            document.getElementById('login-subtitle').textContent = 'Silahkan masuk ke akun anda';
            document.getElementById('btn-login').disabled = false;
            document.getElementById('btn-login').innerHTML = 'Masuk <i class="fa fa-arrow-right ml-1"></i>';
            hideAlert('alert-face');
        });
    }

    // Tombol verifikasi wajah
    var btnFace = document.getElementById('btn-verify-face');
    if (btnFace) {
        btnFace.addEventListener('click', function(e) {
            e.preventDefault();
            doFaceVerify();
        });
    }
});

function doLogin() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value;
    var btnLogin = document.getElementById('btn-login');

    if (!username || !password) {
        showAlert('alert-credentials', 'Username dan password wajib diisi.', 'danger');
        return;
    }

    btnLogin.disabled = true;
    btnLogin.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Memeriksa...';
    hideAlert('alert-credentials');

    $.ajax({
        url: urlAuthenticate,
        method: 'POST',
        data: { username: username, password: password, _token: csrfToken, 'cf-turnstile-response': turnstileToken },
        success: function (res) {
            if (res.step === 'face') {
                document.getElementById('face-nama').textContent = res.nama;
                document.getElementById('step-credentials').style.display = 'none';
                document.getElementById('step-face').style.display = '';
                document.getElementById('login-subtitle').textContent = 'Verifikasi Wajah';
                startCamera();
            }
        },
        error: function (xhr) {
            var msg = 'Username atau password tidak valid.';
            if (xhr.responseJSON) {
                msg = xhr.responseJSON.message ||
                    (xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors).flat().join(' ') : msg);
            }
            showAlert('alert-credentials', msg, 'danger');
            btnLogin.disabled = false;
            btnLogin.innerHTML = 'Masuk <i class="fa fa-arrow-right ml-1"></i>';
        }
    });
}

function doFaceVerify() {
    var btnFace = document.getElementById('btn-verify-face');
    btnFace.disabled = true;
    btnFace.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memverifikasi...';
    hideAlert('alert-face');

    var video  = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0);
    var imageBase64 = canvas.toDataURL('image/jpeg', 0.85).split(',')[1];

    $.ajax({
        url: urlFaceVerify,
        method: 'POST',
        data: { face_image: imageBase64, _token: csrfToken },
        success: function (res) {
            showAlert('alert-face', res.message || 'Verifikasi berhasil!', 'success');
            stopCamera();
            setTimeout(function () { window.location.href = res.url; }, 1000);
        },
        error: function (xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Verifikasi gagal, coba lagi.';
            showAlert('alert-face', msg, 'danger');
            btnFace.disabled = false;
            btnFace.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Verifikasi Wajah';
        }
    });
}

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function (stream) {
            cameraStream = stream;
            var video = document.getElementById('video');
            video.srcObject = stream;
            document.getElementById('cam-placeholder').style.setProperty('display', 'none', 'important');
            document.getElementById('btn-verify-face').disabled = false;
        })
        .catch(function () {
            showAlert('alert-face', 'Kamera tidak dapat diakses. Pastikan izin kamera diberikan.', 'danger');
        });
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(function (t) { t.stop(); });
        cameraStream = null;
    }
}

function showAlert(id, msg, type) {
    var el = document.getElementById(id);
    if (!el) return;
    el.className = 'alert alert-' + type + ' py-2 mb-3';
    el.textContent = msg;
    el.style.display = '';
}

function hideAlert(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'none';
}
