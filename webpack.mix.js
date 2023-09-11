const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

/*const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.webpackConfig({
    plugins: [
        new BrowserSyncPlugin({
            proxy: 'http://127.0.0.1:8000', // Cambia esto por la URL de tu servidor Laravel
            open: false,
        }),
    ],
});
 */