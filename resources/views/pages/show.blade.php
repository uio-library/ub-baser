@extends($page->layout)

@section('content')

    @can($page->permission)
        <p>
            <a href="{{ route($editRoute) }}">[Rediger]</a>
        </p>
    @endif
    <p>
        {!! $page->body !!}
    </p>

@endsection
