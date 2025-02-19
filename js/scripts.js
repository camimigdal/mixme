function root() {
    var scripts = document.getElementsByTagName('script'),
        script = scripts[scripts.length - 1],
        path = script.getAttribute('src').split('/'),
        pathname = location.pathname.split('/'),
        notSame = false,
        same = 0;

    for (var i in path) {
        if (!notSame) {
            if (path[i] == pathname[i]) {
                same++;
            } else {
                notSame = true;
            }
        }
    }
    return location.origin + pathname.slice(0, same).join('/');
}

var WEB_ROOT = root();



$(document).ready(function() {

    $.extend($.easing, {
        easeInOutCubic: function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        }
    });

    /***************** Nav Transformicon ******************/

    /* When user clicks the Icon */
    $('.nav-toggle').click(function() {
        $(this).toggleClass('active');
        $('.header-nav').toggleClass('open');
        event.preventDefault();
    });
    /* When user clicks a link */
    $('.header-nav li a').click(function() {
        $('.nav-toggle').toggleClass('active');
        $('.header-nav').toggleClass('open');

    });

    /***************** Header BG Scroll ******************/

    $(function() {
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 90) {
                $('section.navigation').addClass('fixed');
                $('header').addClass('padding-header-down');
                $('.logo img').addClass('logo-down');
            } else {
                $('section.navigation').removeClass('fixed');
                $('header').removeClass('padding-header-down');
                $('.logo img').removeClass('logo-down');
            }
        });
    });


    // Toggle Search
    $('.show-search').click(function() {
        $('.search-form').css('margin-top', '0');
    });
    $('.close-search').click(function() {
        $('.search-form').css('margin-top', '-60px');
    });


    /***************** Slide Home ******************/

    //Function to animate slider captions 
    function doAnimations(elems) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';

        elems.each(function() {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function() {
                $this.removeClass($animationType);
            });
        });
    }

    //Variables on page load 
    var $myCarousel = $('.carouselHome'),
        $firstAnimatingElems = $myCarousel.find('.carousel-item:first').find("[data-animation ^= 'animated']");

    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);

    //Initialize carousel 
    $myCarousel.carousel({
        interval: 3000,
        pause: "false"
    });

    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function(e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });

    /***************** Smooth Scrolling ******************/

    $(function() {

        // init
        $('body > *:not(style, script)').trigger('add.cards');
        $(window).resize().scroll();

        $(window).scroll(function() {
            if ($(this).scrollTop() > 500) {
                $(".button-back-to-top").removeClass('is-hidden');
            } else {
                $(".button-back-to-top").addClass('is-hidden');
            }
        });
        $(".button-back-to-top").click(function() {
            $('html, body').stop().animate({
                scrollTop: 0
            }, 1400, 'easeInOutCubic');
        });



    });

});

$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})