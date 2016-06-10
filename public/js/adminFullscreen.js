
$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height();
    $(".fullscreen").css('max-height', height - 250);
};

$(function () {
    fullscreenSupport();
});