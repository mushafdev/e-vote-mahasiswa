$('document').ready(function(){
    $('.slide-x').slick({
        autoplay: true,
        dots: true,
        infinite: true,
        speed: 500,
        lazyLoad: 'progressive',
        fade: true,
        cssEase: 'linear',
        prevArrow: '<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
        nextArrow: '<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
        }).slickAnimation();
        $('.slick-nav').on('click touch', function(e) {
        e.preventDefault();

        var arrow = $(this);

        if(!arrow.hasClass('animate')) {
            arrow.addClass('animate');
            setTimeout(() => {
                arrow.removeClass('animate');
            }, 1600);
        }

        });
});
