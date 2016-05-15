$(document).ready(function(){
    var navbar = $('#navbar');
    var menu  = $('#menu-xs');

    $(document).on( 'scroll', function(){
        var currentScrollTop = $(document).scrollTop();

        if( currentScrollTop > 100 ) {
            navbar.css('background-color', '#ffd346');
        } else {
            navbar.css('background-color', 'transparent');
        }
    });

    //$toggle = $('#navbar-toggle');
    //$toggle.click(function() {
    //    $isOpen = $(this).attr('aria-expanded');
    //
    //    if($isOpen) {
    //        menu.css('background-color', '#ffd346');
    //        navbar.css('background-color', '#ffd346');
    //    } else {
    //        menu.css('background-color', 'transparent');
    //
    //        var currentScrollTop = $(document).scrollTop();
    //        console.log(currentScrollTop);
    //        if( currentScrollTop <= 100 ) {
    //            navbar.css('background-color', 'transparent');
    //        }
    //    }
    //});

    var sky = $('.sky');
    var skyHeight = $(document).height();
    sky.css('height', skyHeight - 200);
});

