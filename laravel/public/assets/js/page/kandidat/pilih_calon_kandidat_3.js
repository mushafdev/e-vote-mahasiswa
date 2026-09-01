var cameraStream = null;

$(document).ready(function () {

    var form     = $("#form-pilih");
    var urlSave  = form.data('save');
    var csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

    // Klik "Pilih kandidat ini" → tampilkan kamera face verify
    $('#btn-pilih').on('click', function () {
        $('#step-pilih').hide();
        $('#step-face').show();
        startCamera();
    });

    // Batal verifikasi wajah
    $('#btn-batal-face').on('click', function (e) {
        e.preventDefault();
        stopCamera();
        $('#step-face').hide();
        $('#step-pilih').show();
        hideAlert();
    });

    // Verifikasi wajah & simpan pilihan
    $('#btn-verify-face').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memverifikasi...');
        hideAlert();

        var video  = document.getElementById('video-vote');
        var canvas = document.getElementById('canvas-vote');
        canvas.width  = video.videoWidth  || 640;
        canvas.height = video.videoHeight || 480;
        canvas.getContext('2d').drawImage(video, 0, 0);
        var imageBase64 = canvas.toDataURL('image/jpeg', 0.85).split(',')[1];

        var form_data = new FormData(form[0]);
        form_data.append('face_image', imageBase64);
        form_data.append('_token', csrfToken);

        $.ajax({
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            cache: false,
            url: urlSave,
            success: function (data) {
                stopCamera();
                Swal.fire({
                    title: "Berhasil!",
                    text: 'Anda berhasil memilih kandidat',
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false
                }).then(function () {
                    location.reload();
                });
            },
            error: function (reject) {
                btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Verifikasi & Konfirmasi Pilihan');
                var msg = 'Verifikasi gagal, coba lagi.';
                if (reject.responseJSON && reject.responseJSON.message) {
                    msg = reject.responseJSON.message;
                }
                showAlert(msg, 'danger');
            }
        });
    });

});

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function (stream) {
            cameraStream = stream;
            var video = document.getElementById('video-vote');
            video.srcObject = stream;
            document.getElementById('cam-placeholder').style.setProperty('display', 'none', 'important');
            document.getElementById('btn-verify-face').disabled = false;
        })
        .catch(function () {
            showAlert('Kamera tidak dapat diakses. Pastikan izin kamera diberikan.', 'danger');
        });
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(function (t) { t.stop(); });
        cameraStream = null;
    }
}

function showAlert(msg, type) {
    var el = document.getElementById('alert-face');
    if (!el) return;
    el.className = 'alert alert-' + type + ' py-2 mb-2';
    el.textContent = msg;
    el.style.display = '';
}

function hideAlert() {
    var el = document.getElementById('alert-face');
    if (el) el.style.display = 'none';
}
