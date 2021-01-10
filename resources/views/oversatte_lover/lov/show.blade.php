@extends('oversatte_lover.layout')

@section('content')

    <p>
        @can('oversatte_lover')
            <a href="{{ $base->action('LovController@edit', $record->id) }}" class="btn btn-outline-primary">
                <em class="fa fa-edit"></em>
                Rediger lov
            </a>
            <a href="{{ $base->action('LovController@delete', $record->id) }}" class="btn btn-outline-danger">
                <em class="fa fa-trash"></em>
                Slett lov
            </a>
        @endcan
    </p>

    <h2>
        {{ strval($record) }}
    </h2>

    @if ( $record->trashed() )
        <div class="alert alert-danger">
            Denne loven er slettet.
        </div>
    @endif

    <dl class="row">
        @foreach ($schema->fields as $field)
            @if ($field->showInRecordView && !empty($record->{$field->key}))
                <dt class="col-sm-3 text-sm-right">
                    {{ trans('oversatte_lover.oversettelse.' . $field->key) }}:
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

    <div>
        <h3>Oversettelser</h3>

        <ul>
            @foreach($record->oversettelser as $oversettelse)
                <li>
                    <a href="{{ $base->action('show', $oversettelse->id) }}">{{ $oversettelse->tittel }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
