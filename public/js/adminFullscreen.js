
$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height();
    $(".fullscreen").css('height', height);
};

$(function () {
    fullscreenSupport();
});