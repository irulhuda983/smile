(function ($, window, document, undefined) {

        // REMOVE CSS FROM ELEMENT
        // ------------------------------------------------------------------------------------------------ * --> 
        $.fn.extend({
                removeCss: function (cssName) {
                        return this.each(function () {
                                var curDom = $(this);
                                jQuery.grep(cssName.split(","),

                                function (cssToBeRemoved) {
                                        curDom.css(cssToBeRemoved, '');
                                });
                                return curDom;
                        });
                }
        });

        // MAKE CODE PRETTY
        // ------------------------------------------------------------------------------------------------ * -->
        var $window = $(window)
        window.prettyPrint && prettyPrint();

        // SIDEBAR RESIZE - CONVERT NAV
        // ------------------------------------------------------------------------------------------------ * --> 
        $(window).resize(function () {
                if($(window).width() < 767) {
                        $('.sidebar').addClass('collapse')
                        $('.sidebar, .footer-sidebar').removeCss('display');
                }
                if($(window).width() > 767) {
                        $('.sidebar').removeClass('collapse');
                        $('.sidebar').removeCss('height');

                        if(!$('body').hasClass('sidebar-hidden')) {
                                $('.sidebar, .footer-sidebar').css({
                                        'display': 'block'
                                });
                        } else {
                                $('.sidebar, .footer-sidebar').css({
                                        'display': 'none'
                                });
                        }
                }
        });
        $(function () {
                if($(window).width() < 767) {
                        $('.sidebar').addClass('collapse');
                }
                if($(window).width() > 767) {
                        $('.sidebar').removeClass('collapse');
                        $('.sidebar').removeCss('height');
                }
        });
		$('body').addClass('sidebar-hidden');
        // SCROLL - NICESCROLL
        // ------------------------------------------------------------------------------------------------ * -->
        // The document page (body)
        /**/$("html").niceScroll({
					cursoropacitymin:0.1,
					cursoropacitymax:0.9,
					cursorcolor:"#adafb5",
					cursorwidth:"8px",
					cursorborder:"",
					cursorborderradius:"8px",
					usetransition:600,
					background:"",
					railoffset:{top:10,left:-3}	
				}); 
				
				$("#main-sidebar").niceScroll({
					cursoropacitymin:0.1,
					cursoropacitymax:0.9,
					cursorcolor:"#adafb5",
					cursorwidth:"6px",
					cursorborder:"",
					cursorborderradius:"6px",
					usetransition:600,
					background:"",
					railoffset:{top:10,left:-1}
				});
				/*
				$(".bodyscroll").niceScroll({
					autohidemode: false,
					cursoropacitymin:0.9,
					cursoropacitymax:0.9,
					cursorcolor:"#adafb5",
					cursorwidth:"6px",
					cursorborder:"",
					cursorborderradius:"6px",
					usetransition:600,
					railoffset:{top:10,left:-1,bottom:-10}
				});
				*/

        // SCROLL TOP PAGE
        // ------------------------------------------------------------------------------------------------ * -->
        $(window).scroll(function () {
                if($(this).scrollTop() > 100) {
                        $('#btnScrollup').fadeIn('slow');
                } else {
                        $('#btnScrollup').fadeOut(600);
                }
        });

        $('#btnScrollup').click(function () {
                $("html, body").animate({
                        scrollTop: 0
                }, 500);
                return false;
        });
		

})(jQuery, this, document);