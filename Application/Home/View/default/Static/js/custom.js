//Auto Close Responsive Navbar on Click
    function close_toggle() {
        if ($(window).width() <= 768) {
            $('.navbar-collapse a').on('click', function () {
                $('.navbar-collapse').collapse('hide');
            });
        }
        else {
            $('.navbar .navbar-default a').off('click');
        }
    }
    close_toggle();
    $(window).resize(close_toggle);

    //hero text fade flexslider
    $(window).load(function () {
        $('.flexslider').flexslider({
            controlNav: false,
            directionNav: false,
            slideshowSpeed: 4000
        });
    });

    //home slider
    $(window).load(function () {
        $('.main-slider').flexslider({
            controlNav: false,
            directionNav: true,
            slideshowSpeed: 4000,
            prevText: "<i class='ion-chevron-left'></i>",
            nextText: "<i class='ion-chevron-right'></i>"
        });
    });
	//home slider
    (function(){

        var config = {
          viewFactor : 0.15,
          duration   : 800,
          distance   : "0px",
          scale      : 0.8
        };

        window.sr = ScrollReveal( config );

        if (sr.isSupported()) {
          document.documentElement.classList.add('sr');
        }
      })();
	  //home counter
	  $('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
 //home scrool
var $ = $.noConflict();
$(document).ready(function () {
    "use strict";
    if ($('.scrollReveal').length && !$('html.ie9').length) {
        $('.scrollReveal').parent().css('overflow', 'hidden');
        window.sr = ScrollReveal({
            reset: true,
            distance: '32px',
            mobile: true,
            duration: 850,
            scale: 1,
            viewFactor: 0.3,
            easing: 'ease-in-out'
        });
        sr.reveal('.sr-top', {origin: 'top'});
        sr.reveal('.sr-bottom', {origin: 'bottom'});
        sr.reveal('.sr-left', {origin: 'left'});
        sr.reveal('.sr-long-left', {origin: 'left', distance: '70px', duration: 1000});
        sr.reveal('.sr-right', {origin: 'right'});
        sr.reveal('.sr-scaleUp', {scale: '0.8'});
        sr.reveal('.sr-scaleDown', {scale: '1.15'});

        sr.reveal('.sr-delay-1', {delay: 200});
        sr.reveal('.sr-delay-2', {delay: 400});
        sr.reveal('.sr-delay-3', {delay: 600});
        sr.reveal('.sr-delay-4', {delay: 800});
        sr.reveal('.sr-delay-5', {delay: 1000});
        sr.reveal('.sr-delay-6', {delay: 1200});
        sr.reveal('.sr-delay-7', {delay: 1400});
        sr.reveal('.sr-delay-8', {delay: 1600});

        sr.reveal('.sr-ease-in-out-quad', {easing: 'cubic-bezier(0.455,  0.030, 0.515, 0.955)'});
        sr.reveal('.sr-ease-in-out-cubic', {easing: 'cubic-bezier(0.645,  0.045, 0.355, 1.000)'});
        sr.reveal('.sr-ease-in-out-quart', {easing: 'cubic-bezier(0.770,  0.000, 0.175, 1.000)'});
        sr.reveal('.sr-ease-in-out-quint', {easing: 'cubic-bezier(0.860,  0.000, 0.070, 1.000)'});
        sr.reveal('.sr-ease-in-out-sine', {easing: 'cubic-bezier(0.445,  0.050, 0.550, 0.950)'});
        sr.reveal('.sr-ease-in-out-expo', {easing: 'cubic-bezier(1.000,  0.000, 0.000, 1.000)'});
        sr.reveal('.sr-ease-in-out-circ', {easing: 'cubic-bezier(0.785,  0.135, 0.150, 0.860)'});
        sr.reveal('.sr-ease-in-out-back', {easing: 'cubic-bezier(0.680, -0.550, 0.265, 1.550)'});
    }
});
// Wait for window load
	$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
// js loader
/* -------------------------------------------------------- */
//Sliders owlCarousel - start
/* -------------------------------------------------------- */
   $(document).ready(function () {
	    $("#owl-clients").owlCarousel({
        navigation: true,
        items: 6,
        itemsDesktop: [1200, 6],
        itemsTablet: [800, 3],
        itemsMobile: [700, 2],
		autoPlay : true,
		pagination : false,
    paginationNumbers: false,
    });
	
   
  }); 
/* -------------------------------------------------------- */
//  Owl Carousels - end
/* -------------------------------------------------------- */

/* -------------------------------------------------------- */
// Scroll To Top - start
/* -------------------------------------------------------- */
    $(window).scroll(function () {
        if ($(this)
                .scrollTop() > 100) {
            $('.scrollTop')
                    .fadeIn();
        } else {
            $('.scrollTop')
                    .fadeOut();
        }
    });
    $('.scrollTop').on('click', function () {
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
        return false;
    });
/* -------------------------------------------------------- */
// Scroll To Top - end
/* -------------------------------------------------------- */






