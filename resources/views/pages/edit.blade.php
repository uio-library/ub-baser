@extends($page->layout)

@section('content')

    <form method="POST" action="{{ action('PageController@update', ['page' => $page->slug]) }}">
        <?php echo csrf_field(); ?>

        <page-editor data="{{ $page->body }}"></page-editor>

        <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>

        <a href="{{ action('PageController@show', ['page' => $page->slug]) }}" class="btn btn-default">{{ trans('messages.cancel') }}</a>

    </form>

@endsection
