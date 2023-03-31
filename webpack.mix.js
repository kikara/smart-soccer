const mix = require('laravel-mix');

// mix.js(['resources/js/app.js'], 'public/js')
//     .js(['resources/js/debugPage.js'], 'public/js')
//     .sass('resources/css/app.scss', 'public/css')
//     .sourceMaps()
// ;

// mix

mix.js('resources/js/app', 'public/js')
    .vue();
    // .sass('resources/css/app.scss', 'public/css')
//
// mix.sass('resources/css/app.scss', 'public/css');


// mix.js(['resources/js/game_index.js'], 'public/js')
//     .js('resources/js/debug_index.js', 'public/js')
//     .sourceMaps();
