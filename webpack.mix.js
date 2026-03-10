const mix = require('laravel-mix');
const webpack = require('webpack'); // <--- Añade esta línea

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .alias({
        '@': 'resources/js',
    })
    .webpackConfig({ // <--- Añade este bloque
        plugins: [
            new webpack.DefinePlugin({
                '__VUE_OPTIONS_API__': true,
                '__VUE_PROD_DEVTOOLS__': false,
                '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': false,
            }),
        ],
    });

if (mix.inProduction()) {
    mix.version();
}