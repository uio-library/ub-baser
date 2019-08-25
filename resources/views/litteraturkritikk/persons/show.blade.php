@extends('layouts.litteraturkritikk')

@section('content')

    <p>
        @can('litteraturkritikk')
        <a href="{{ action('LitteraturkritikkPersonController@edit', $person->id) }}"><i class="fa fa-edit"></i> Rediger person</a>
        @endcan
    </p>

    <h2>
        {{ strval($person) }}
    </h2>

    @if ( $person->trashed() )
        <div class="alert alert-danger">
            Denne personen er slettet.
        </div>
    @endif

    @if ($person->kjonn)
        <div>Kjønn: {{ $person->kjonnRepr() }}</div>
    @endif

    @if ($person->kommentar)
        <div>
            {{ trans('litteraturkritikk.kommentar') }}:
            {{ $person->kommentar }}
        </div>
    @endif

    @if ($person->bibsys_id)
        <div>
            {{ trans('litteraturkritikk.bibsys_id') }}:
            {{ $person->bibsys_id }}
        </div>
    @endif

    @if ($person->wikidata_id)
        <div>
            {{ trans('litteraturkritikk.wikidata_id') }}:
            {{ $person->wikidata_id }}
        </div>
    @endif

    @if (count($person->records_as_forfatter))
    <h3>Forfatter e.l. av verk omtalt i {{ count($person->records_as_forfatter ) }} kritikker</h3>
    <ul>
        @foreach ($person->records_as_forfatter as $record)
            <li>{!! $record->representation() !!}</li>
        @endforeach
    </ul>
    <a href="{{ action('LitteraturkritikkController@index', ['f0' => 'verk_forfatter', 'v0' => strval($person)])  }}">Vis som tabellvisning</a>
    @endif

    @if (count($person->records_as_kritiker))
        <h3>Skribent av {{ count($person->records_as_kritiker ) }} kritikker</h3>
        <ul>
            @foreach ($person->records_as_kritiker as $record)
                <li>{!! $record->representation() !!}</li>
            @endforeach
        </ul>
        <a href="{{ action('LitteraturkritikkController@index', ['f0' => 'kritiker', 'v0' => strval($person)])  }}">Vis som tabellvisning</a>
    @endif

@endsection
