var elixir = require('laravel-elixir');

require('laravel-elixir-bower');

/*
 |--------------------------------------------------------------------------
 | Compile Sass
 |--------------------------------------------------------------------------
 */

// elixir(function(mix) {
//     mix.sass('app.scss');
// });

/*
 |--------------------------------------------------------------------------
 | Concat Bower files
 |--------------------------------------------------------------------------
 |
 | This will scan your bower files and concat all css files in a
 | styles/vendor.css file, and all js files in a scripts/vendor.js file.
 |
 */


elixir(function(mix) {
    mix.bower();
});
