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
    /*.sass('resources/sass/oasis/invoice.scss', 'public/css/oasis-invoice.css', {
        implementation: require('node-sass')
    })*/
    .webpackConfig({
        resolve: {
            alias: {
                "@assets": path.resolve(__dirname, "resources/sass"),
                "@": path.resolve(__dirname, "resources/js"),
                "@modules": path.resolve(__dirname, "node_modules"),
            }
        },
        output: {
            chunkFilename: '[name].js?id=[chunkhash]',
        },
        //devtool: 'inline-source-map',
        devServer: {
            clientLogLevel: 'none'
        }
    })
    .options({
        hmrOptions: {
            host: '192.168.1.198',
            port: '8080'
        },
    })
    .disableNotifications();

if (mix.inProduction()) {
    mix.version();
}