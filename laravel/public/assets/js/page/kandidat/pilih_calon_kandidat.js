$('document').ready(function(){
    var form=$("#form-kandidat");
    var urlSearchKandidat=$("#form-search").data('search-kandidat');
    var urlGetKandidat=$("#form-search").data('detail-kandidat');
    var urlPilihKandidat=$("#form-pilih").data('pilih-kandidat');
    var faceRequired=$("#form-pilih").data('face-registered') === '1' || $("#form-pilih").data('face-registered') === 1;
    var urlSave=form.data('save');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    });

    var input = document.getElementById("keyword");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("search-button").click();
        }
    });

    $("#search-button").click(function(e) {
        e.preventDefault();
        if($("#form-search").parsley().validate()){
            $.ajax({
                type    : 'GET',
                data    : {keyword:$("#keyword").val()},
                url     : urlSearchKandidat,
                dataType:'json',
                beforeSend: function() { $('.loading-data').show(); },
                success : function(data) {
                    $('.loading-data').hide();
                    $('#count_show').html(data.total_show);
                    $('#table-result').html(data.table);
                },
                error: function (reject) {
                    if( reject.status === 422 ) {
                        var jsonResponse = JSON.parse(reject.responseText);
                        $.each(jsonResponse.errors, function(key, value) {
                            $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        });
                    } else {
                        Swal.fire("Error", "There is an error", "error");
                    }
                }
            });
        }
    });

    $(document).on('click', '#t_kandidat tbody tr', function () {
        $('.edited').html('-');
        $('#face_image').val('');
        $('#face-status').html('');
        var id = $(this).data("id");
        $('#modal-update').modal('show');
        $.ajax({
            type    : 'GET',
            data    : {id:id},
            url     : urlGetKandidat,
            dataType: 'json',
            beforeSend: function() { $('.modal-load').show(); },
            success : function(data) {
                $('#id').val(data.id);
                $('#no_anggota_i').html(data.q.no_anggota);
                $('#nama_i').html(data.q.nama);
                $('.modal-load').hide();
                // Aktifkan kamera otomatis jika face required
                if (faceRequired) {
                    startFaceCamera();
                }
            }
        });
    });

    $("#pilih").click(function(e) {
        e.preventDefault();

        // Validasi: wajah wajib diambil jika face_registered
        if (faceRequired && !$('#face_image').val()) {
            Swal.fire("Perhatian", "Silahkan ambil foto wajah terlebih dahulu untuk verifikasi identitas", "warning");
            return;
        }

        if($("#form-pilih").parsley().validate()){
            var param = this;
            process1(param);
            var form_data = new FormData($('#form-pilih')[0]);
            ax_save = $.ajax({
                type    : 'POST',
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async: true,
                url     : urlPilihKandidat,
                success : function(data) {
                    process_done1(param,'<i class="fa fa-save"></i> Pilih Kandidat Ini');
                    if(data.status == 500){
                        Swal.fire("Maaf", data.message, "error");
                    } else {
                        stopFaceCamera();
                        Swal.fire({
                            title: "Berhasil!",
                            text: 'Kandidat berhasil dipilih',
                            type: "success",
                            timer: 2000
                        }).then(() => { location.reload(); });
                    }
                },
                error: function (reject) {
                    process_done1(param,'<i class="fa fa-save"></i> Pilih Kandidat Ini');
                    if (reject.status === 422) {
                        var jsonResponse = JSON.parse(reject.responseText);
                        var msg = jsonResponse.message || 'Data tidak valid';
                        Swal.fire("Gagal", msg, "error");
                    } else {
                        Swal.fire("Error", "Terjadi kesalahan, silahkan coba lagi", "error");
                    }
                }
            });
        }
    });

    // Reset kamera saat modal ditutup
    $('#modal-update').on('hidden.bs.modal', function () {
        stopFaceCamera();
        $('#face_image').val('');
        $('#face-status').html('');
        $('#face-canvas').hide();
        $('#face-video').show();
        $('#btn-capture-face').hide();
        $('#btn-retake-face').hide();
        $('#btn-start-face-cam').show();
    });
});

// ── Kamera Face Verification ──────────────────────────────────────────────────

var faceStream = null;

function startFaceCamera() {
    var video = document.getElementById('face-video');
    if (!video) return;
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(function(stream) {
            faceStream = stream;
            video.srcObject = stream;
            $('#btn-start-face-cam').hide();
            $('#btn-capture-face').show();
            $('#face-canvas').hide();
            $('#face-video').show();
            $('#face-status').html('<span class="text-info">Kamera aktif. Arahkan wajah Anda ke kamera.</span>');
        })
        .catch(function(err) {
            $('#face-status').html('<span class="text-danger">Kamera tidak dapat diakses: ' + err.message + '</span>');
        });
}

function stopFaceCamera() {
    if (faceStream) {
        faceStream.getTracks().forEach(function(track) { track.stop(); });
        faceStream = null;
    }
}

$('#btn-start-face-cam').on('click', function() {
    startFaceCamera();
});

$('#btn-capture-face').on('click', function() {
    var video  = document.getElementById('face-video');
    var canvas = document.getElementById('face-canvas');
    if (!video || !canvas) return;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    var base64 = canvas.toDataURL('image/jpeg', 0.85);
    $('#face_image').val(base64);
    $('#face-video').hide();
    $('#face-canvas').show();
    $('#btn-capture-face').hide();
    $('#btn-retake-face').show();
    $('#face-status').html('<span class="text-success"><i class="fa fa-check"></i> Foto wajah berhasil diambil. Klik <b>Pilih Kandidat Ini</b> untuk melanjutkan.</span>');
});

$('#btn-retake-face').on('click', function() {
    $('#face_image').val('');
    $('#face-canvas').hide();
    $('#face-video').show();
    $('#btn-retake-face').hide();
    $('#btn-capture-face').show();
    $('#face-status').html('<span class="text-info">Arahkan wajah Anda ke kamera.</span>');
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.fileName = input.files[0].name;
        reader.onload = function(e) {
            $('#img_show').attr('src', e.target.result);
            $('#file_name').text(e.target.fileName);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$("#photo").change(function() { readURL(this); });
