@extends($page->layout)

@section('content')

    <form method="POST" action="{{ action('PageController@update', ['page' => $page->slug]) }}">
        <?php echo csrf_field(); ?>

        @if (!$page->exists)
        <p class="alert alert-success">
            OBS: Du er i ferd med å opprette en ny side.
        </p>
        @endif

        <p>
            Språk for denne siden: {{ \Punic\Language::getName(\App::getLocale(), 'nb') }}
        </p>

        <page-editor
            data="{{ $page->body }}"
            image-upload-url="{{ action('PageController@uploadImage') }}"
            csrf-token="{{ csrf_token() }}"
            update-url="{{ action('PageController@update', ['page' => $page->slug]) }}"
        ></page-editor>

    </form>

@endsection
