@extends($page->layout)

@section('content')

    @can($page->permission)
        <p>
            <a href="{{ action('PageController@edit', ['page' => $page->slug]) }}"><em class="fa fa-edit"></em> Rediger tekst</a>
        </p>
    @endif
    <div class="fr-view">
        {!! $page->body !!}
    </div>


    <hr>
    <p class="text-muted text-right">
        <small>
            Sist endret av {{ $page->updatedBy->name }} {{ $page->updated_at }}
        </small>
    </p>

@endsection
