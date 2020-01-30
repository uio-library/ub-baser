@extends('litteraturkritikk.layout')

@section('content')

    <p>
        @can('litteraturkritikk')
            <a href="{{ $base->action('WorkController@edit', ['id' => $record->id]) }}"><em class="fa fa-edit"></em> Rediger verk</a>
        @endcan
    </p>

    @if ( $record->trashed() )
        <div class="alert alert-danger">
            Dette verket er slettet.
        </div>
    @endif

    <h2>{{ $record->verk_tittel }} ({{ $record->verk_dato }})</h2>
    <div>
        av
        @foreach ($record->forfattere as $entity)
            @component('litteraturkritikk.components.person', [
                'record' => $entity,
                'schema' => \App\Bases\Litteraturkritikk\PersonSchema::class,
            ])
            @endcomponent
        @endforeach
        @if ($record->forfattere_mfl)
            <em>mfl.</em>
        @endif
    </div>

    @component('litteraturkritikk.components.work', [
        'record' => $record,
        'schema' => $schema,
        'hide' => ['verk_tittel']
    ])
    @endcomponent

    <div>
        <h3 class="mt-3">Omtalt i {{ count($record->discussedIn ) }} kritikk(er)</h3>
        <ul>
            @foreach ($record->discussedIn as $kritikk)
            <li>
                <a href="{{ $base->action('show', ['id' => $kritikk->id]) }}">
                    {!! $kritikk->representation() !!}
                </a>
            </li>
            @endforeach
        </ul>

        <a href="{{ $base->action('index', [
            'q' => $queryStringBuilder->build([
                ['verk_tittel', 'contains', $record->verk_tittel, 'AND'],
                ['forfatter', 'contains', count($record->forfattere) > 0 ? strval($record->forfattere[0]) : ''],
            ]) ]) }}">Vis i søk</a>
    </div>

@endsection
