@extends($page->layout)

@section('content')

    <form method="POST" action="{{ action('PageController@update', ['page' => $page->slug]) }}">
        <?php echo csrf_field(); ?>

        <p>
            Språk for denne siden: {{ \Punic\Language::getName(\App::getLocale(), 'nb') }}
        </p>

        <page-editor
            :existed="{{ $page->exists ? 'true' : 'false' }}"
            data="{{ $page->body }}"
            image-upload-url="{{ action('PageController@uploadImage') }}"
            csrf-token="{{ csrf_token() }}"
            update-url="{{ action('PageController@update', ['page' => $page->slug]) }}"
            lock-url="{{ action('PageController@lock', ['page' => $page->slug]) }}"
            unlock-url="{{ action('PageController@unlock', ['page' => $page->slug]) }}"
        ></page-editor>

    </form>

@endsection
