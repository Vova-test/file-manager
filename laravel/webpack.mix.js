const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.styles('resources/css/modal_popup.css', 'public/css/modal_popup.css');
mix.styles('resources/css/my-style.css', 'public/css/my-style.css');
mix.styles('resources/css/task-js.css', 'public/css/task-js.css');

mix.scripts('resources/js/bootstrap.js', 'public/js/bootstrap.js');
mix.scripts('resources/js/file.blade.js', 'public/js/file.blade.js');
