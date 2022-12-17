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

mix.js('resources/js/app.js', 'public/js').vue()
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

mix.copy('node_modules/datatables.net-plugins/i18n/no-NB.json', 'public/misc/datatables-nb.json')

mix.copy('node_modules/openseadragon/build/openseadragon/images/*', 'public/images')


/*
 |--------------------------------------------------------------------------
 | And then some horrible CKEditor mess
 | https://github.com/ckeditor/ckeditor5-vue/issues/23#issuecomment-455756667
 |--------------------------------------------------------------------------
 */

const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const {styles} = require('@ckeditor/ckeditor5-dev-utils');

// make sure you copy these two regexes from the CKEdidtor docs:
// https://ckeditor.com/docs/ckeditor5/latest/installation/advanced/alternative-setups/integrating-from-source.html#webpack-configuration
const CKERegex = {
  svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
  css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/,
};

Mix.listen('configReady', webpackConfig => {
  const rules = webpackConfig.module.rules;

  // these change often! Make sure you copy the correct regexes for your Webpack version!
  const targetSVG = /(\.(png|jpe?g|gif|webp|avif)$|^((?!font).)*\.svg$)/;
  const targetFont = /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/;
  const targetCSS = /\.p?css$/;

  // exclude CKE regex from mix's default rules
  for (let rule of rules) {
    // console.log(rule.test) // uncomment to check the CURRENT rules

    if (rule.test.toString() === targetSVG.toString()) {
      rule.exclude = CKERegex.svg;
    } else if (rule.test.toString() === targetFont.toString()) {
      rule.exclude = CKERegex.svg;
    } else if (rule.test.toString() === targetCSS.toString()) {
      rule.exclude = CKERegex.css;
    }
  }
});

mix.webpackConfig({
  plugins: [
    new CKEditorWebpackPlugin({
      language: 'nb',
      addMainLanguageTranslationsToAllAssets: true
    })
  ],
  module: {
    rules: [
      {
        test: CKERegex.svg,
        use: ['raw-loader']
      },
      {
        test: CKERegex.css,
        use: [
          {
            loader: 'style-loader',
            options: {
              injectType: 'singletonStyleTag',
              attributes: {
                'data-cke': true
              }
            }
          },
          'css-loader', // ADDED
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: styles.getPostCssConfig({ // moved into option `postcssOptions`
                themeImporter: {
                  themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
                },
                minify: true
              })
            }
          }
        ]
      }
    ]
  }
});
