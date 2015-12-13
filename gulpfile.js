var elixir = require('laravel-elixir');

require('laravel-elixir-bower');

elixir(function(mix) {

    mix
        .sass('app.scss')
        .styles([

            // Bootstrap
            'bootstrap/dist/css/bootstrap.css',

            // Slider component
            'bootstrap-slider/dist/css/bootstrap-slider.css',

            // Selectize
            'selectize/dist/css/selectize.css',

            // Icon fonts
            'font-awesome/css/font-awesome.css',
            'material-design-iconic-font/dist/css/material-design-iconic-font.css',

            // Froala WYSIWYG editor
            'froala-editor/css/froala_editor.min.css',
            'froala-editor/css/froala_style.min.css',
            'froala-editor/css/plugins/char_counter.min.css',
            'froala-editor/css/plugins/code_view.min.css',
            'froala-editor/css/plugins/colors.min.css',
            'froala-editor/css/plugins/emoticons.min.css',
            'froala-editor/css/plugins/file.min.css',
            'froala-editor/css/plugins/fullscreen.min.css',
            'froala-editor/css/plugins/image_manager.min.css',
            'froala-editor/css/plugins/image.min.css',
            'froala-editor/css/plugins/line_breaker.min.css',
            'froala-editor/css/plugins/table.min.css',
            'froala-editor/css/plugins/video.min.css',

        ], 'public/css/vendor.css', './node_modules/')
        .scripts([

            // jQuery
            'jquery/dist/jquery.js',

            // Bootstrap (Uncomment if we start using any of the JS components)
            // 'bootstrap/dist/js/bootstrap.js',

            // Slider component
            'bootstrap-slider/js/bootstrap-slider.js',

            // Selectize
            'microplugin/src/microplugin.js',
            'sifter/sifter.js',
            'selectize/dist/js/selectize.js',

        ], 'public/js/vendor.js', './node_modules/')
        .scripts([

            // Froala WYSIWYG editor
            'froala-editor/js/froala_editor.min.js',
            'froala-editor/js/plugins/align.min.js',
            'froala-editor/js/plugins/char_counter.min.js',
            'froala-editor/js/plugins/code_beautifier.min.js',
            'froala-editor/js/plugins/code_view.min.js',
            'froala-editor/js/plugins/colors.min.js',
            'froala-editor/js/plugins/emoticons.min.js',
            'froala-editor/js/plugins/entities.min.js',
            'froala-editor/js/plugins/file.min.js',
            'froala-editor/js/plugins/font_family.min.js',
            'froala-editor/js/plugins/font_size.min.js',
            'froala-editor/js/plugins/fullscreen.min.js',
            'froala-editor/js/plugins/image.min.js',
            'froala-editor/js/plugins/image_manager.min.js',
            'froala-editor/js/plugins/inline_style.min.js',
            'froala-editor/js/plugins/line_breaker.min.js',
            'froala-editor/js/plugins/link.min.js',
            'froala-editor/js/plugins/lists.min.js',
            'froala-editor/js/plugins/paragraph_format.min.js',
            'froala-editor/js/plugins/paragraph_style.min.js',
            'froala-editor/js/plugins/quote.min.js',
            'froala-editor/js/plugins/save.min.js',
            'froala-editor/js/plugins/table.min.js',
            'froala-editor/js/plugins/url.min.js',
            'froala-editor/js/plugins/video.min.js',

        ], 'public/js/editing.js', './node_modules/')
        .version([
            'public/css/app.css',
            'public/css/vendor.css',
            'public/js/vendor.js',
            'public/js/editing.js',
        ])
        .copy('bower_components/font-awesome/fonts', 'public/build/fonts')
        .copy('bower_components/material-design-iconic-font/dist/fonts', 'public/build/fonts');

});
