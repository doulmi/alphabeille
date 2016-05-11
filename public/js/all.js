

$(document).ready(function(){
    var navbar = $('#navbar');
    var menu  = $('#menu-xs');

    $(document).on( 'scroll', function(){
        var currentScrollTop = $(document).scrollTop();

        if( currentScrollTop > 100 ) {
            navbar.css('background-color', '#ffd346');
            menu.css('background-color', '#ffd346');
        } else {
            navbar.css('background-color', 'transparent');
            menu.css('background-color', 'transparent')
        }
    });

    var sky = $('.sky');
    var  skyHeight = $(document).height();
    sky.css('height', skyHeight - 200);
});

