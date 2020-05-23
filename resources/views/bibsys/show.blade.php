@extends('base.show')

@section('record')
    <h4 class="mt-4">Objektpost</h4>
    <div class="col-sm-9 text-sm text-monospace" style="white-space: pre">{!! $record->getFormattedMarcrecord() !!}</div>

    <h4 class="mt-4">Dokumentpost</h4>
    <dl class="row">
        @foreach ($schema->groups[1]->fields as $field)
            @if ($field->showInRecordView)
                <dt class="col-sm-3 text-sm-right text-monospace">{{ $field->label }}</dt>
                <dd class="col-sm-9 text-sm text-monospace {{ $field->key }}">
                    @if ($field->key == 'har_hefter')
                        @if ($record->{$field->key} == '1')
                            <a href="{{ $base->action('index', ['q' => 'seriedokid eq ' . $record->dokid]) }}">Vis hefter</a>
                        @else
                            <em>Nei</em>
                        @endif
                    @elseif ($field->key == 'seriedokid')
                        <a href="{{ $base->action('show', ['id' => $record->{$field->key}]) }}">{{ $record->{$field->key} }}</a>
                    @else
                        {{ $record->{$field->key} }}
                    @endif
                </dd>
            @endif
        @endforeach
    </dl>
@endsection
