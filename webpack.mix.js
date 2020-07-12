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

mix.js('resources/js/main.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css/app.css', {
        implementation: require('node-sass')
    })
    .webpackConfig({
        resolve: {
            alias: {
                "@assets": path.resolve(__dirname, "resources/sass"),
                "@": path.resolve(__dirname, "resources/js"),
            }
        },
    })
/*    .options({
        hmrOptions: {
            host: '172.20.10.5',
            port: '8080'
        },
    })*/
    .disableNotifications();
