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
mix.browserSync({
    proxy: 'http://localhost:8000',
    open: false,
    notify: false
});
// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');

mix.styles([
    'resources/plantilla/css/style.css',
    'resources/plantilla/css/examples.css',
    'resources/plantilla/css/prism.css',
    'resources/plantilla/css/simplebar.css',
    'resources/plantilla/css/font-awesome.min.css',
    'resources/plantilla/css/simple-line-icons.css'
], 'public/css/plantilla.css')
.scripts([
    'resources/plantilla/js/popovers.js',
    'resources/plantilla/js/prism-autoloader.min.js',
    'resources/plantilla/js/prism-normalize-whitespace.js',
    'resources/plantilla/js/prism-unescaped-markup.min.js',
    'resources/plantilla/js/prism.js',
    'resources/plantilla/js/simplebar.min.js',
    'resources/plantilla/js/toasts.js',
    'resources/plantilla/js/tooltips.js',
], 'public/js/plantilla.js')
.js(['resources/js/app.js'],'public/js/app.js')
.vue()
.copy('resources/css/app.css', 'public/css/app.css')
.copy('resources/css/fontawesome.min.css', 'public/css/fontawesome.min.css');