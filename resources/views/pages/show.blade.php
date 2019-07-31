@extends($page->layout)

@section('content')

    @can($page->permission)
        <p>
            <a href="{{ action('PageController@edit', ['page' => $page->slug]) }}"><i class="fa fa-edit"></i> Rediger tekst</a>
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
