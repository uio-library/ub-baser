@extends('layouts.letras')

@section('content')

    <h2>
        Post {{ $record->id }}
    </h2>
    @can('letras')
        <p>
            <a href="{{ action('LetrasController@edit', $record->id) }}">[Rediger]</a>
        </p>
    @endif

    @foreach ($schema['groups'] as $group)
        <h4 class="mt-4">{{ $group['label'] }}</h4>
        <dl class="row">
            @foreach ($group['fields'] as $field)
                @if (!isset($field['display']) || $field['display'] !== false)
                    <dt class="col-sm-3 text-sm-right">
                        {{ trans('letras.' . $field['key']) }}:
                    </dt>
                    <dd class="col-sm-9">
                        {{ $record->{$field['key']} }}
                    </dd>
                @endif
            @endforeach
        </dl>
    @endforeach

@endsection