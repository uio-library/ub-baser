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
        <i class="fa fa-user"></i>
        {{ strval($record) }}
    </h2>

    @if ( $record->trashed() )
        <div class="alert alert-danger">
            Denne personen er slettet.
        </div>
    @endif

    <div class="ltk_record">
        @foreach ($schema->fields as $field)
            @if ($field->showInRecordView && !empty($record->{$field->key}))
                <div>
                    <div class="ltk_field_name">
                        {{ trans('litteraturkritikk.person.' . $field->key) }}:
                    </div>
                    <div class="ltk_field_value">
                        @if (method_exists($field, 'formatValue'))
                            {!! $field->formatValue($record->{$field->key}, $base) !!}
                        @else
                            {{ $record->{$field->key} }}
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    @if (count($record->recordsAsCritic))
        <div id="recordsAsCritic" class="mt-3">
            <h3>Skribent av {{ count($record->recordsAsCritic ) }} {{ count($record->recordsAsCritic ) == 1 ? 'kritikk' : 'kritikker' }}</h3>
            <ul>
                @foreach ($record->recordsAsCritic as $doc)
                    <li>{!! $doc->representation() !!}</li>
                @endforeach
            </ul>
            <a href="{{ $base->action('index', [
                'q' => $queryStringBuilder->build([
                    ['kritiker', 'contains', strval($record)],
                ]) ]) }}">Vis i søk</a>
        </div>
    @endif

    @if (count($record->discussedIn))
        <div id="recordsDiscussedIn" class="mt-3">
            <h3>Forfatterskap omtalt i {{ count($record->discussedIn ) }} {{ count($record->discussedIn) == 1 ? 'kritikk' : 'kritikker' }}</h3>
            <ul>
                @foreach ($record->discussedIn as $doc)
                    <li>{!! $doc->representation() !!}</li>
                @endforeach
            </ul>
            <a href="{{ $base->action('index', [
                'q' => $queryStringBuilder->build([
                    ['forfatter', 'contains', strval($record), 'AND'],
                    ['verk_tittel', 'isnull'],
                ]) ]) }}">Vis i søk</a>
        </div>
    @endif

    @if (count($record->works))
        <div id="recordsAsAuthor" class="mt-3">
            <h3>
                Verk ({{ count($record->works) }})
            </h3>
            <ul>
                @foreach ($record->works as $work)
                    <li>
                        <a href="{{ $base->action('WorkController@show', $work->id) }}">
                            {!! $work->stringRepresentation !!}
                        </a>
                        <ul>
                            @foreach ($work->discussedIn as $critique)
                                <li>
                                    {!! $critique->representation() !!}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
            <a href="{{ $base->action('index', [
                'q' => $queryStringBuilder->build([
                    ['forfatter', 'contains', strval($record), 'AND'],
                    ['verk_tittel', 'notnull'],
                ]) ]) }}">Vis i søk</a>
        </div>
    @endif


@endsection
