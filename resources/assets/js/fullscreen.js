$(document).ready(function () {
    fullscreenSupport();
});

$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height();
    var width = $(window).width();
    $(".fullscreen").css('min-height', height);
};