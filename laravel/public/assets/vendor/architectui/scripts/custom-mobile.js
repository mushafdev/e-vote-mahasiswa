//New Custome
if ($(window).width() > 812) {
  location.replace('http://mercusuar.uzone.id');
}
$(document).ready(function(){
  $("#profile-picture").click(function() { 
    if($(".dropdown-content").hasClass("d-block")){
      $('.dropdown-content').removeClass("d-block");		
    }else{
      $('.dropdown-content').addClass("d-block");
    }	
  }); 

  
  $('#zoomBtn').click(function() {
    $('.zoom-btn-sm').toggleClass('scale-out');
    if (!$('.zoom-card').hasClass('scale-out')) {
      $('.zoom-card').toggleClass('scale-out');
    }
  });
  
  $('.zoom-btn-sm').click(function() {
    var btn = $(this);
    var card = $('.zoom-card');
    if ($('.zoom-card').hasClass('scale-out')) {
      $('.zoom-card').toggleClass('scale-out');
    }
    if (btn.hasClass('zoom-btn-person')) {
      card.css('background-color', '#d32f2f');
    } else if (btn.hasClass('zoom-btn-doc')) {
      card.css('background-color', '#fbc02d');
    } else if (btn.hasClass('zoom-btn-tangram')) {
      card.css('background-color', '#388e3c');
    } else if (btn.hasClass('zoom-btn-report')) {
      card.css('background-color', '#1976d2');
    } else {
      card.css('background-color', '#7b1fa2');
    }
  });

});

function loadPageMobile(x){
    $('#content').empty();
    $('#loading').show();
    $('#content').load(x);    
  }

  function loadPageMobileInner(x){
    $('#content-inner').empty();
    $('#loading').show();
    $('#content-inner').load(x);
  }


  function process1(param){
    $(param).addClass('buttonload');
    $(param).html('<i class="fa fa-sync fa-spin"></i>');
    $(param).prop('disabled',true);
    $(param).click(function(){return false;});
  }
  
  function process_done1(param,text){
    $(param).removeClass('buttonload');
    $(param).html(text);
    $(param).prop('disabled',false);
    $(param).click(function(){return true;});
  }

  $(window).on('load', function(){
    $('#loading').fadeIn();  
      setTimeout(function () {
        $('#loading').fadeOut();   
      }, 200);
  });

  $(function(){

    $(window).scroll(function(){
      var aTop = $('.header-home').height();
      if($(this).scrollTop()>=aTop){
        $('.logo-fixed').removeClass("hide");
      }else{
        $('.logo-fixed').addClass("hide");
      }
    });

  });

  function goBack() {
    window.history.back();
  }

  function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

  var blob = new Blob(byteArrays, {type: contentType});
  return blob;
}





 