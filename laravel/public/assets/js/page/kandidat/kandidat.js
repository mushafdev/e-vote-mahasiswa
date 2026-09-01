
if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';
CKEDITOR.config.removePlugins = 'easyimage, cloudservices';

var editor1='';

var wysiwygareaAvailable = isWysiwygareaAvailable(),
isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

var editorElement = CKEDITOR.document.getById( 'editor' );

// :(((
if ( isBBCodeBuiltIn ) {
    editorElement.setHtml(
        'Hello world!\n\n' +
        'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
    );
}

// Depending on the wysiwygarea plugin availability initialize classic or inline editor.
if ( wysiwygareaAvailable ) {
    var editor1 = CKEDITOR.replace( 'editor',{
        // filebrowserUploadMethod: 'form',
        filebrowserImageBrowseUrl: '../../../filemanager?type=Images',
        filebrowserImageUploadUrl: '../../../filemanager/upload?type=Images&_token={{csrf_token()}}',
        filebrowserBrowseUrl: '../../../filemanager?type=Files',
        filebrowserUploadUrl: '../../../filemanager/upload?type=Files&_token={{csrf_token()}}'
    }
    );
} else {
    editorElement.setAttribute( 'contenteditable', 'true' );
    CKEDITOR.inline( 'editor' );

    // TODO we can consider displaying some info box that
    // without wysiwygarea the classic editor may not work.
}

function isWysiwygareaAvailable() {
    // If in development mode, then the wysiwygarea must be available.
    // Split REV into two strings so builder does not replace it :D.
    if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
        return true;
    }

    return !!CKEDITOR.plugins.get( 'wysiwygarea' );
}


$('document').ready(function(){

    var form=$("#form-kandidat");
    var urlSave=form.data('save');
    var urlDetail=form.data('detail');
    var urlUpdate=form.data('update');
    var urlMain=form.data('main');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })



    $('.modal-load').hide();

    $("#save").click(function(e) {
        e.preventDefault();
        if($("#form-kandidat").parsley().validate()){
        var param = this;
        process1(param);
        const editorData = editor1.getData();
        var form = $('form')[0];
        var form_data = new FormData(form);
        form_data.append("deskripsi", editorData);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlSave,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Simpan');
            Swal.fire({
                    title: "Success!",
                    text: 'Data sukses disimpan',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.replace(urlMain);

                });
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                
                $('#form-kandidat').parsley().reset();
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    $.each( jsonResponse.errors, function( key, value) {
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                    });
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false();
        }

    });

    $("#update").click(function(e) {
        e.preventDefault();
        if($("#form-kandidat").parsley().validate()){
        var param = this;
        process1(param);
        const editorData = editor1.getData();
        var form = $('form')[0];
        var form_data = new FormData(form);
        form_data.append("deskripsi", editorData);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlUpdate,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Simpan');
                Swal.fire("Success!",'Data sukses diupdate',"success");
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                $('#form-kandidat').parsley().reset();
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    $.each( jsonResponse.errors, function( key, value) {
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                    });
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false();
        }

    });


});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.fileName = input.files[0].name;
        reader.onload = function(e) {
            $('#img_show').attr('src', e.target.result);
            $('#file_name').text(e.target.fileName);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


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

const input = document.querySelector('#photo');
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
            quality: 0.4,
            type: 'image/jpeg',
        });

        // Save back the compressed file instead of the original file
        dataTransfer.items.add(compressedFile);
    }
    // console.log(dataTransfer.files);
    // Set value of the file input to our new files list
    e.target.files = dataTransfer.files;
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.fileName = e.target.files[0].name;
        reader.onload = function(e) {
            $('#img_show').attr('src', e.target.result);
            $('#file_name').text(e.target.fileName);
        }

        reader.readAsDataURL(e.target.files[0]);
    }
});