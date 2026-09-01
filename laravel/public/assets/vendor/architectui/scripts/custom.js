//New Custome
$('document').ready(function(){
$('.select2').select2();
  $(".vertical-nav-menu").metisMenu();
  $(".close-sidebar-btn").click(function(){
    $(".app-container").toggleClass("closed-sidebar");
    $(".close-sidebar-btn").toggleClass("is-active");
    $(".logo-src img").toggleClass("small");
    $(".brand").toggleClass("hide");
    $(".scrollbar-sidebar").toggleClass("m-top-0");
  });
  $(".mobile-toggle-nav").click(function(){
    $(this).toggleClass("is-active");
    $(".app-container").toggleClass("sidebar-mobile-open");
  });
  $(".mobile-toggle-header-nav").click(function(){
    $(this).toggleClass("active");
    $(".app-header__content").toggleClass("header-mobile-open");
  });

  $(".navigation-list").click(function(){
    $(".navigation-list").removeClass('mm-active');
    $(this).addClass('mm-active');
    $(".app-container").removeClass("sidebar-mobile-open");
  });


  $(".app-sidebar").hover(function() {
    if($(".app-container").hasClass("closed-sidebar")){
      $(".logo-src img").addClass("hide");
      $(".scrollbar-sidebar").addClass("m-top-0-up");
    }

  }, function() {
    if($(".app-container").hasClass("closed-sidebar")){
      $(".logo-src img").removeClass("hide");
      $(".scrollbar-sidebar").removeClass("m-top-0-up");
    }
  });

  new PerfectScrollbar(".scrollbar-sidebar",{wheelSpeed:2,wheelPropagation:!1,minScrollbarLength:20});
});
function loadPage(x){
    $(".hamburger").removeClass("is-active");
    $('#content').empty();
    $('#loading').show();
    $('#content').load(x);
  }

function loadPageInner(x){
  $('#content-inner').empty();
  $('#loading-inner').show();
  $('#content-inner').load(x);
}


  function process1(param){
    $(param).addClass('buttonload');
    $(param).html('<i class="fa fa-sync fa-spin"></i> Processed...');
    $(param).prop('disabled',true);
    $(param).click(function(){return false;});
  }

  function process_done1(param,text){
    $(param).removeClass('buttonload');
    $(param).html(text);
    $(param).prop('disabled',false);
    $(param).click(function(){return true;});
  }

  function totop(){
    $("html").animate({ scrollTop: 0 }, "fast");
  }





