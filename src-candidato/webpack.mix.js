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
    .sass('resources/css/candidate.scss','public/css');

mix.js('resources/js/candidate.js', 'public/js')

mix.js('resources/js/lottery.js', 'public/js')

mix.copy('resources/js/datatables.pt-br.json','public/js/datatables.pt-br.json')

/*
Arquivos CookieBar Gov.BR
 */
mix.copy('resources/css/cookiebar_govbr.css','public/css')
mix.copy('resources/js/application_cookiebar_1_3_55.js','public/js')
mix.copy('resources/js/block_cookies.js','public/js')
mix.copy('resources/js/changeCookieImage.js','public/js')
mix.copy('resources/js/cookiebar_1_3_55.js','public/js')
mix.copy('resources/js/lgpd_cookie_handling_1_3_55.js','public/js')
mix.copy('resources/js/configuracoes_cookiebar_govbr.json','public/js')
//fim
