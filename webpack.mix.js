// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin

const mix = require('laravel-mix')
require('laravel-vue-lang/mix')
require('laravel-mix-imagemin')

const imageminMozjpeg = require('imagemin-mozjpeg')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.lang()

mix.js('resources/js/app.js', 'public/js')
  .sourceMaps()
  .version()

mix.imagemin({
  from: 'images/*',
}, {
  context: 'resources',
},{
  optipng: {
    optimizationLevel: 3
  },
  jpegtran: null,
  plugins: [
    imageminMozjpeg({
      quality: 75,
      progressive: true,
    }),
  ],
})

// mix.copy('./resources/images/*', 'public/images')

mix.sass('resources/sass/app.sass', 'public/css')
  .version()

mix.styles([

  // Bootstrap
  // 'node_modules/bootstrap/dist/css/bootstrap.css',

  // Selectize
  'node_modules/selectize/dist/css/selectize.css',

  // Icon fonts
  'node_modules/font-awesome/css/font-awesome.css',
  'node_modules/material-design-iconic-font/dist/css/material-design-iconic-font.css',

  // DataTables
  // 'node_modules/datatables.net-dt/css/jquery.dataTables.css',
  'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css',

  // Bootstrap-select
  'node_modules/bootstrap-select/dist/css/bootstrap-select.css',

], 'public/css/vendor.css')

mix.version([
  'public/css/vendor.css',
  // 'public/js/vendor.js',
])

// mix.copy('node_modules/bootstrap/fonts', 'public/fonts');

mix.copy('node_modules/font-awesome/fonts', 'public/fonts')

mix.copy('node_modules/material-design-iconic-font/dist/fonts', 'public/fonts')

mix.copy('node_modules/datatables.net-dt/images/*', 'public/images')

mix.copy('node_modules/datatables.net-plugins/i18n/Norwegian-Bokmal.lang', 'public/misc/datatables-nb.json')

mix.copy('node_modules/openseadragon/build/openseadragon/images/*', 'public/images')

mix.webpackConfig({
  plugins: [
    // new BundleAnalyzerPlugin({
    //     analyzerMode: 'disabled',
    //     generateStatsFile: true,
    //     // statsOptions: { source: false }
    // }),
  ],
})
