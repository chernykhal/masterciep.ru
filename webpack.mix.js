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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'));

mix.browserSync({
    proxy:  // проксирование вашего удаленного сервера, не важно на чем back-end
        {
            target: "https://masterciep.test",
            ws: true
        },
    // logPrefix: 'masterciep.test', // префикс для лога bs, маловажная настройка
    // host: 'masterciep.test', // можно использовать ip сервера
    port: 8000, // порт через который будет проксироваться сервер
    open: 'external', // указываем, что наш url внешний
    notify: true,
    ghost: true,
    https: true
     // httpModule: 'http2',
     // https: {
     //    key: "./ssl/privkey.pem",
     //    cert: "./ssl/fullchain.pem",
     // },
});
