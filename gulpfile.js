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
    mix.scripts(['dropdowns-enhancement.js', 'fullscreen.js', 'tooltip.js','googleAnalyse.js', 'toastr.min.js', 'jquery.gotop.min.js', 'app.js'], 'public/js/app.js');
    mix.styles(['toastr.min.css','dropdowns-enhancement.min.css', 'tooltips.css', 'app.css', 'menu.css' ], 'public/css/app.css');
});
