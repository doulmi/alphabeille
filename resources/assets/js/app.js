$(document).ready(function () {
    var navbar = $('#navbar');
    var menu = $('#menu-xs');

    var lastScrollTop = 0;
    $(document).on( 'scroll', function(){
        var currentScrollTop = $(document).scrollTop();

        var isScrollTop = (currentScrollTop - lastScrollTop) < 0;

        if(isScrollTop) {
            navbar.show();
        } else {
            if (currentScrollTop > 100) {
                navbar.fadeOut();
            } else {
                navbar.show();
            }
        }
        lastScrollTop = currentScrollTop;
    });


    //
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
    sky.css('height', skyHeight - 150);

    toastr.options = {
        "positionClass": "toast-top-center"
    };

    //$('.md-header').hide();
});

function search() {
    var searchForm = $('#searchForm');
    searchForm.submit();
}


