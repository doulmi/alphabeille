
$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height() - 135;
    $(".fullscreen").css('height', height);
};

$(function () {
    fullscreenSupport();
});