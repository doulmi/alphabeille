var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts(['dropdowns-enhancement.js', 'fullscreen.js', 'tooltip.js','googleAnalyse.js', 'toastr.min.js', 'jquery.gotop.min.js', 'bootstrap-markdown.js', 'jquery.joyride-2.1.js', 'app.js', 'social-share.min.js'], 'public/js/app.js');
    mix.sass(['csshake.min.css', /*'CoconBold.css'm*/ 'toastr.min.css','dropdowns-enhancement.min.css', 'tooltips.css', 'video-js.css',  'menu.css', 'audioplayer.css', 'share.min.css', 'bootstrap-markdown.min.css', 'joyride-2.1.css', 'app.scss'], 'public/css/app.css');
});
