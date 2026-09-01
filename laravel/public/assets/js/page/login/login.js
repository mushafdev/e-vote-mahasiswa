

$('document').ready(function(){   
    $("#show_hide_password .show-pass").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').hasClass("pw-dots")){
            $('#show_hide_password input').removeClass('pw-dots');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }else{
            $('#show_hide_password input').addClass('pw-dots');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }
        
         var x = document.getElementById("password");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
    });

    var urlReload=$('#login').data('reload-captcha');
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: urlReload,
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

    $('input').on('keyup change', function() {
        $(this).attr('autocomplete', 'off');
    });

    
});

function showPass() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

 