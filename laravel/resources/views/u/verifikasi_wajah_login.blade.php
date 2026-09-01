<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <title>{{Formatter::identity()['title']}} - Verifikasi Wajah</title>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/poppins-font.css')}}"/>
    <link href="{{asset('assets/vendor/fontawesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/login.css')}}">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container-fluid">
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <img src="{{asset('assets/images/logo-simple.png')}}" alt="" style="height:50px;" onerror="this.style.display='none'">
                        <h5 class="mt-2 font-weight-bold">Verifikasi Wajah</h5>
                        <p class="text-muted small">Halo <strong>{{ $user->nama }}</strong>, arahkan wajah ke kamera lalu klik Verifikasi.</p>
                    </div>

                    {{-- Kamera --}}
                    <div class="position-relative mb-3" style="background:#000;border-radius:8px;overflow:hidden;">
                        <video id="video" autoplay playsinline style="width:100%;display:block;border-radius:8px;"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <div id="placeholder" class="d-flex flex-column align-items-center justify-content-center text-white" style="position:absolute;top:0;left:0;width:100%;height:100%;background:#1a1a2e;min-height:220px;">
                            <i class="fas fa-camera fa-3x mb-2 text-secondary"></i>
                            <small>Kamera belum aktif</small>
                        </div>
                    </div>

                    <div id="alert-box" class="alert d-none py-2 small"></div>

                    <div class="d-flex gap-2">
                        <button id="btn-verifikasi" class="btn btn-primary btn-block" disabled>
                            <i class="fas fa-check-circle mr-1"></i> Verifikasi Wajah
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ url('login') }}" class="text-muted small"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script>
var urlVerifikasi = '{{ route("login.verifikasi_wajah_proses") }}';
var csrfToken = '{{ csrf_token() }}';
var stream = null;

// Mulai kamera
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(function (s) {
        stream = s;
        var video = document.getElementById('video');
        video.srcObject = s;
        document.getElementById('placeholder').style.setProperty('display', 'none', 'important');
        document.getElementById('btn-verifikasi').disabled = false;
    })
    .catch(function () {
        showAlert('Kamera tidak dapat diakses. Pastikan izin kamera diberikan.', 'danger');
    });

$('#btn-verifikasi').on('click', function () {
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memverifikasi...');
    showAlert('', '');

    // Ambil foto dari video
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    var imageBase64 = canvas.toDataURL('image/jpeg', 0.85).split(',')[1];

    $.ajax({
        url: urlVerifikasi,
        method: 'POST',
        data: { face_image: imageBase64, _token: csrfToken },
        success: function (res) {
            showAlert(res.message || 'Verifikasi berhasil!', 'success');
            if (stream) stream.getTracks().forEach(t => t.stop());
            setTimeout(function () { window.location.href = res.url; }, 1000);
        },
        error: function (xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Verifikasi gagal, coba lagi.';
            showAlert(msg, 'danger');
            btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Verifikasi Wajah');
        }
    });
});

function showAlert(msg, type) {
    var box = $('#alert-box');
    if (!msg) { box.addClass('d-none'); return; }
    box.removeClass('d-none alert-success alert-danger alert-warning')
       .addClass('alert-' + type)
       .text(msg);
}
</script>
</body>
</html>
