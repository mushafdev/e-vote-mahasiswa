

$('document').ready(function(){

    // Toggle show/hide password
    $('#show_hide_password_reg .show-pass').on('click', function () {
        var input = $('#password');
        var icon  = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    $('.loading-data').hide();
    $("#modalTahapan").modal('show');
    var form=$("#form-registrasi");
    var urlSave=form.data('save');
    var urlSearchKandidat=$("#form-search").data('search-kandidat');
   $('.form-video').hide();
   
    $("#search-data").click(function(e) {
        e.preventDefault();
        $('#keyword').val('');
        $('#search-errors').html('');
        $('#count_show').html('');
        $('#table-result').html('');
        $("#modalPencarian").modal('show');
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
                beforeSend: function() {
                    $('.loading-data').show();
                },
                success : function(data) {
                    $('.loading-data').hide();
                    $('#count_show').html(data.total_show);
                    $('#table-result').html(data.table);
                },
                error: function (reject) {
                    $('#form-kandidat').parsley().reset();
                    if( reject.status === 422 ) {
                        var data=reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        $.each( jsonResponse.errors, function( key, value) {
                            $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        });
                        // var no_anggotaError=jsonResponse.errors.no_anggota;
                        // if(no_anggotaError !== undefined){
                        //     $('#no_anggota').parsley().addError('uniqueerror', {message: no_anggotaError, updateClass: true});
                        // }
                    }else{
                        Swal.fire("Error", "There is an error", "error");
                    }
                }
            });
        }else {
            return false;
        }
    });

    $(document).on( 'click', '#t_kandidat tbody tr', function () {
        $('.edited').html('-');
        var nama=$(this).closest('tr').find('td:eq(1)').text();
        var no_anggota=$(this).closest('tr').find('td:eq(2)').text();
        $('#nama').val(nama);
        $('#no_anggota').val(no_anggota);

        $("#modalPencarian").modal('hide');
    });
    
    $("#registrasi").click(function(e) {
        e.preventDefault();
        if($("#form-registrasi").parsley().validate()){

        // Validasi minimal 1 foto wajah sudah diambil
        var takenPhotos = capturedPhotos.filter(Boolean);
        if (takenPhotos.length === 0) {
            $('#file_photo').text('Foto wajah wajib diambil minimal 1x');
            return false;
        }
        $('#file_photo').text('');

        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);

        // Gunakan foto pertama sebagai file_photo (foto profil)
        var firstPhoto = takenPhotos[0];
        var blobBin = atob(firstPhoto.split(',')[1]);
        var array = [];
        for(var i = 0; i < blobBin.length; i++) { array.push(blobBin.charCodeAt(i)); }
        var file = new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
        form_data.append("file_photo", file);

        // face_images[] sudah ada di form via hidden inputs, ikut otomatis di FormData
        // (face_image_1 .. face_image_5 → name="face_images[]")
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlSave,
            DataType: 'json',
            success : function(data) {
            process_done1(param,'Registrasi');
            Swal.fire({
                    title: "Success!",
                    text: 'Data sukses dikirim',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.replace(data.url);

                });
            },
            error: function (reject) {
                process_done1(param,'Registrasi');

                $('#form-registrasi').parsley().reset();
                $('#file_photo').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    $.each( jsonResponse.errors, function( key, value) {
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                    });
                }else if( reject.status === 403 || reject.status === 400 ) {
                    Swal.fire({
                        title: "Captcha expire!",
                        text: 'Halaman akan direfresh. Silahkan ulangi',
                        type: "error",
                        timer: 2000
                    }).then((result) => {
                        location.reload();
                    });
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false;
        }

    });
});



// ── Kamera & multi-foto capture ────────────────────────────────────────────
var videoEl      = document.getElementById('video');
var canvasEl     = document.getElementById('canvas');
var cameraStream = null;
var TOTAL_PHOTOS = 5; // jumlah foto registrasi: depan/kiri/kanan/menunduk/mendongak
var photoStep    = 0; // 0 = belum mulai, 1..TOTAL_PHOTOS = foto ke-n
var capturedPhotos = new Array(TOTAL_PHOTOS).fill(null);

var poseLabels = ['Hadap depan', 'Sedikit ke kiri', 'Sedikit ke kanan', 'Menunduk sedikit', 'Mendongak sedikit'];

function updateProgress() {
    for (var i = 1; i <= TOTAL_PHOTOS; i++) {
        var dot = document.getElementById('dot-' + i);
        if (capturedPhotos[i-1]) {
            dot.style.background = '#28a745';
            dot.style.color      = '#fff';
            dot.style.borderColor = '#28a745';
        } else if (i === photoStep) {
            dot.style.background  = '#007bff';
            dot.style.color       = '#fff';
            dot.style.borderColor = '#007bff';
        } else {
            dot.style.background  = '';
            dot.style.color       = '';
            dot.style.borderColor = '';
        }
    }
    var taken = capturedPhotos.filter(Boolean).length;
    $('#photo-count-label').text('(' + Math.min(taken + 1, TOTAL_PHOTOS) + '/' + TOTAL_PHOTOS + ')');
    if (photoStep > 0 && photoStep <= TOTAL_PHOTOS) {
        $('#pose-guide').text(poseLabels[photoStep - 1]);
    }
}

document.getElementById('start-camera').addEventListener('click', async function () {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        videoEl.srcObject = cameraStream;
        document.getElementById('cam-placeholder').style.setProperty('display', 'none', 'important');
        document.getElementById('video').style.display = 'block';
        document.getElementById('canvas').style.display = 'none';
        document.getElementById('start-camera').style.display = 'none';
        document.getElementById('click-photo').style.display = 'block';
        document.getElementById('retake-photo').style.display = 'none';
        photoStep = 1;
        updateProgress();
    } catch (e) {
        Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + e.message, 'error');
    }
});

document.getElementById('click-photo').addEventListener('click', function () {
    if (photoStep < 1 || photoStep > TOTAL_PHOTOS) return;

    // Capture ke canvas utama
    canvasEl.getContext('2d').drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);
    var dataUrl = canvasEl.toDataURL('image/jpeg', 0.85);

    // Simpan ke hidden input
    capturedPhotos[photoStep - 1] = dataUrl;
    document.getElementById('face_image_' + photoStep).value = dataUrl;

    // Update preview thumbnail
    var prev = document.getElementById('prev-' + photoStep);
    prev.getContext('2d').drawImage(canvasEl, 0, 0, prev.width, prev.height);
    prev.style.borderColor = '#28a745';

    if (photoStep < TOTAL_PHOTOS) {
        photoStep++;
        updateProgress();
        document.getElementById('retake-photo').style.display = 'block';
    } else {
        // Semua foto selesai
        updateProgress();
        document.getElementById('video').style.display        = 'none';
        document.getElementById('canvas').style.display       = 'block';
        document.getElementById('click-photo').style.display  = 'none';
        document.getElementById('retake-photo').style.display = 'block';
        document.getElementById('retake-photo').textContent   = 'Ulangi Semua';
        document.getElementById('pose-guide').textContent     = '✓ ' + TOTAL_PHOTOS + ' foto berhasil diambil';
        document.getElementById('pose-guide').className       = 'badge badge-success';
    }
});

document.getElementById('retake-photo').addEventListener('click', function () {
    capturedPhotos = new Array(TOTAL_PHOTOS).fill(null);
    for (var i = 1; i <= TOTAL_PHOTOS; i++) {
        document.getElementById('face_image_' + i).value = '';
        var prev = document.getElementById('prev-' + i);
        prev.getContext('2d').clearRect(0, 0, prev.width, prev.height);
        prev.style.borderColor = '#ccc';
    }
    photoStep = 1;
    updateProgress();
    document.getElementById('canvas').style.display           = 'none';
    document.getElementById('cam-placeholder').style.display  = 'none';
    document.getElementById('video').style.display            = 'block';
    document.getElementById('click-photo').style.display      = 'block';
    document.getElementById('retake-photo').style.display     = 'none';
    document.getElementById('pose-guide').textContent         = 'Hadap depan';
    document.getElementById('pose-guide').className           = 'badge badge-info';
});

const compressImage = async (file, { quality = 1, type = file.type }) => {
    // Get as image data
    const imageBitmap = await createImageBitmap(file);

    // Draw to canvas
    const canvas = document.createElement('canvas');
    canvas.width = imageBitmap.width;
    canvas.height = imageBitmap.height;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(imageBitmap, 0, 0);

    // Turn into Blob
    const blob = await new Promise((resolve) =>
        canvas.toBlob(resolve, type, quality)
    );

    // Turn Blob into File
    return new File([blob], file.name, {
        type: blob.type,
    });
};

// Get the selected file from the file input
const input = document.querySelector('#ktp');
input.addEventListener('change', async (e) => {
    // Get the files
    const { files } = e.target;

    // No files selected
    if (!files.length) return;

    // We'll store the files in this data transfer object
    const dataTransfer = new DataTransfer();

    // For every file in the files list
    for (const file of files) {
        // We don't have to compress files that aren't images
        if (!file.type.startsWith('image')) {
            // Ignore this file, but do add it to our result
            dataTransfer.items.add(file);
            continue;
        }

        // We compress the file by 50%
        const compressedFile = await compressImage(file, {
            quality: 0.5,
            type: 'image/jpeg',
        });

        // Save back the compressed file instead of the original file
        dataTransfer.items.add(compressedFile);
    }
    // console.log(dataTransfer.files);
    // Set value of the file input to our new files list
    e.target.files = dataTransfer.files;
});

