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

    @if ($person->pseudonym)
        <div>
            {{ trans('litteraturkritikk.pseudonym') }}:
            {{ $person->pseudonym }}
        </div>
    @endif
    @if ($person->pseudonym_for)
        <div>
            {{ trans('litteraturkritikk.pseudonym_for') }}:
            {{ $person->pseudonym_for }}
        </div>
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

    @if (count($person->records_as_kritiker))
    <h3>Forfatter av</h3>
    <ul>
    @foreach ($person->records_as_kritiker as $record)
        <li>{!! $record->representation() !!}</li>
    @endforeach
    </ul>
    @endif


    @if (count($person->records_as_forfatter))
    <h3>Omtalt i</h3>
    <ul>
        @foreach ($person->records_as_forfatter as $record)
            <li>{!! $record->representation() !!}</li>
        @endforeach
    </ul>
    @endif

@endsection
