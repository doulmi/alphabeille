$(document).ready(function () {
    fullscreenSupport();
});

$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height() - 80;
    var width = $(window).width();
    $(".fullscreen").css('min-height', height);
};