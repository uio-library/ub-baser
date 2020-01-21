@extends($page->layout)

@section('content')

    <form method="POST" action="{{ action('PageController@update', ['page' => $page->slug]) }}">
        <?php echo csrf_field(); ?>

        @if (!$page->exists)
        <p class="alert alert-success">
            OBS: Du er i ferd med å opprette en ny side.
        </p>
        @endif

        @if (!$page->exists)
        <p>
            Språk for denne siden: {{ \Punic\Language::getName(\App::getLocale(), 'nb') }}
        </p>
        @endif
        <page-editor data="{{ $page->body }}"></page-editor>

        <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>

        <a href="{{ action('PageController@show', ['page' => $page->slug]) }}" class="btn btn-default">{{ trans('messages.cancel') }}</a>

    </form>

@endsection
