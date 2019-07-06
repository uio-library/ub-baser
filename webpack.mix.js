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

mix.js('./resources/js/app.js', './public/js')
    .sass('./resources/sass/app.scss', './public/css');


mix.styles([

    // Bootstrap
    './node_modules/bootstrap/dist/css/bootstrap.css',

    // Slider component
    './node_modules/bootstrap-slider/dist/css/bootstrap-slider.css',

    // Selectize
    './node_modules/selectize/dist/css/selectize.css',

    // Icon fonts
    './node_modules/font-awesome/css/font-awesome.css',
    './node_modules/material-design-iconic-font/dist/css/material-design-iconic-font.css',

    // Froala WYSIWYG editor
    './node_modules/froala-editor/css/froala_editor.min.css',
    './node_modules/froala-editor/css/froala_style.min.css',
    './node_modules/froala-editor/css/plugins/char_counter.min.css',
    './node_modules/froala-editor/css/plugins/code_view.min.css',
    './node_modules/froala-editor/css/plugins/fullscreen.min.css',
    //'froala-editor/css/plugins/image_manager.min.css',
    //'froala-editor/css/plugins/image.min.css',
    './node_modules/froala-editor/css/plugins/line_breaker.min.css',
    './node_modules/froala-editor/css/plugins/table.min.css',

    // DataTables
    // './node_modules/datatables.net-dt/css/jquery.dataTables.css',
    './node_modules/datatables.net-bs/css/dataTables.bootstrap.css',

    // Bootstrap-select
    './node_modules/bootstrap-select/dist/css/bootstrap-select.css',

], './public/css/vendor.css');


mix.scripts([

    // jQuery
    './node_modules/jquery/dist/jquery.js',

    // Bootstrap (include, since we use Bootstrap-select)
    './node_modules/bootstrap/dist/js/bootstrap.js',

    // Slider component
    './node_modules/bootstrap-slider/js/bootstrap-slider.js',

    // Selectize
    './node_modules/microplugin/src/microplugin.js',
    './node_modules/sifter/sifter.js',
    './node_modules/selectize/dist/js/selectize.js',

    // DataTables
    './node_modules/datatables.net/js/jquery.dataTables.js',
    './node_modules/datatables.net-dt/js/dataTables.dataTables.js',
    './node_modules/datatables.net-bs/js/dataTables.bootstrap.js',

    // Bootstrap-select
    './node_modules/bootstrap-select/dist/js/bootstrap-select.js',
    './node_modules/bootstrap-select/dist/js/i18n/defaults-nb_NO.js',

], './public/js/vendor.js');

mix.scripts([

    // Froala WYSIWYG editor
    './node_modules/froala-editor/js/froala_editor.min.js',
    './node_modules/froala-editor/js/plugins/align.min.js',
    './node_modules/froala-editor/js/plugins/char_counter.min.js',
    './node_modules/froala-editor/js/plugins/code_beautifier.min.js',
    './node_modules/froala-editor/js/plugins/code_view.min.js',
    './node_modules/froala-editor/js/plugins/entities.min.js',
    './node_modules/froala-editor/js/plugins/fullscreen.min.js',
    // './node_modules/froala-editor/js/plugins/image.min.js',
    // './node_modules/froala-editor/js/plugins/image_manager.min.js',
    './node_modules/froala-editor/js/plugins/inline_style.min.js',
    './node_modules/froala-editor/js/plugins/line_breaker.min.js',
    './node_modules/froala-editor/js/plugins/link.min.js',
    './node_modules/froala-editor/js/plugins/lists.min.js',
    './node_modules/froala-editor/js/plugins/paragraph_format.min.js',
    './node_modules/froala-editor/js/plugins/quote.min.js',
    './node_modules/froala-editor/js/plugins/save.min.js',
    './node_modules/froala-editor/js/plugins/table.min.js',
    './node_modules/froala-editor/js/plugins/url.min.js',

], './public/js/editing.js');


mix.version([
    './public/css/app.css',
    './public/css/vendor.css',
    './public/js/vendor.js',
    './public/js/editing.js',
]);

mix.copy('./node_modules/bootstrap/fonts', './public/fonts');

mix.copy('./node_modules/font-awesome/fonts', './public/fonts');

mix.copy('./node_modules/material-design-iconic-font/dist/fonts', './public/fonts');

mix.copy('./node_modules/datatables.net-dt/images/*', './public/images');

mix.copy('./node_modules/datatables.net-plugins/i18n/Norwegian-Bokmal.lang', './public/misc/datatables-nb.json');