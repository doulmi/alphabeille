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
    mix.scripts(['jquery.min.js', 'bootstrap.min.js', 'app.js', 'dropdowns-enhancement.js', 'fullscreen.js', 'tooltip.js'], 'public/js/app.js');
    mix.styles(['bootstrap.min.css', 'app.css', 'dropdowns-enhancement.min.css', 'tooltips.css'], 'public/css/app.css');
});
