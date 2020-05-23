// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin

const mix = require('laravel-mix')
require('laravel-vue-lang/mix')
// require('laravel-mix-imagemin')

const imageminMozjpeg = require('imagemin-mozjpeg')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.lang()

mix.js('resources/js/app.js', 'public/js')
  .sourceMaps(true, 'inline-source-map')
  .version()

/*
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
*/

mix.copy('./resources/images/*', 'public/images')

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


/*
 |--------------------------------------------------------------------------
 | And then some horrible CKEditor mess
 | https://github.com/ckeditor/ckeditor5-vue/issues/23#issuecomment-455756667
 |--------------------------------------------------------------------------
 */

const CKEditorWebpackPlugin = require( '@ckeditor/ckeditor5-dev-webpack-plugin' );
const CKEStyles = require('@ckeditor/ckeditor5-dev-utils').styles;
const CKERegex = {
  svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
  css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
};

Mix.listen('configReady', webpackConfig => {
  const rules = webpackConfig.module.rules;
  const targetSVG = /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/;
  const targetCSS = /\.css$/;

  // exclude CKE regex from mix's default rules
  // if there's a better way to loop/change this, open to suggestions
  for (let rule of rules) {
    if (rule.test.toString() === targetSVG.toString()) {
      rule.exclude = CKERegex.svg;
    }
    else if (rule.test.toString() === targetCSS.toString()) {
      rule.exclude = CKERegex.css;
    }
  }
});

mix.webpackConfig({
  plugins: [
    new CKEditorWebpackPlugin({
      language: 'nb'
    }),
    // new BundleAnalyzerPlugin(),
  ],
  module: {
    rules: [
      {
        test: CKERegex.svg,
        use: [ 'raw-loader' ]
      },
      {
        test: CKERegex.css,
        use: [
          {
            loader: 'style-loader',
            options: {
              injectType: 'singletonStyleTag',
            }
          },
          {
            loader: 'postcss-loader',
            options: CKEStyles.getPostCssConfig({
              themeImporter: {
                themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
              },
              minify: true
            })
          },
        ]
      }
    ]
  }
});
