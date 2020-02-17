@extends('litteraturkritikk.layout')

@section('content')

    <p>
        @can('litteraturkritikk')
            <a href="{{ $base->action('PersonController@edit', $record->id) }}" class="btn btn-outline-primary">
                <em class="fa fa-edit"></em>
                Rediger person
            </a>
            <a href="{{ $base->action('PersonController@delete', $record->id) }}" class="btn btn-outline-danger">
                <em class="fa fa-trash"></em>
                Slett person
            </a>
        @endcan
    </p>

    <h2>
        {{ strval($record) }}
    </h2>

    @if ( $record->trashed() )
        <div class="alert alert-danger">
            Denne personen er slettet.
        </div>
    @endif

    <dl class="row">
        @foreach ($schema->fields as $field)
            @if ($field->displayable && !empty($record->{$field->key}))
                <dt class="col-sm-3 text-sm-right">
                    {{ trans('litteraturkritikk.person.' . $field->key) }}:
                </dt>
                <dd class="col-sm-9">
                    @if (method_exists($field, 'formatValue'))
                        {!! $field->formatValue($record->{$field->key}, $base) !!}
                    @else
                        {{ $record->{$field->key} }}
                    @endif
                </dd>
            @endif
        @endforeach
    </dl>

    @if (count($record->recordsAsAuthor))
        <div id="recordsAsAuthor">
            <h3>Omtalt i {{ count($record->recordsAsAuthor ) }} {{ count($record->recordsAsAuthor ) == 1 ? 'kritikk' : 'kritikker' }}</h3>
            <ul>
                @foreach ($record->recordsAsAuthor as $doc)
                    <li>{!! $doc->representation() !!}</li>
                @endforeach
            </ul>
            <a href="{{ $base->action('index', ['f0' => 'verk_forfatter', 'v0' => strval($record)])  }}">Vis som tabellvisning</a>
        </div>
    @endif

    @if (count($record->recordsAsCritic))
        <div id="recordsAsCritic">
            <h3>Skribent av {{ count($record->recordsAsCritic ) }} {{ count($record->recordsAsCritic ) == 1 ? 'kritikk' : 'kritikker' }}</h3>
            <ul>
                @foreach ($record->recordsAsCritic as $doc)
                    <li>{!! $doc->representation() !!}</li>
                @endforeach
            </ul>
            <a href="{{ $base->action('index', ['f0' => 'kritiker', 'v0' => strval($record)])  }}">Vis som tabellvisning</a>
        </div>
    @endif

@endsection
