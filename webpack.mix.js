const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

var path = require('path');
mix.webpackConfig({
    resolve: {
      modules: [
        path.resolve(__dirname),
        path.resolve('./node_modules/'),
        path.resolve('./resources/')
      ]
    }
});

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/index.css', 'public/css', [
        require("tailwindcss")
    ]);
