@extends('base.show')

@section('record')
    <h4 class="mt-4">Objektpost</h4>
    <div class="col-sm-9 text-sm text-monospace" style="white-space: pre">{!! $record->marc_record_formatted !!}</div>

    <h4 class="mt-4">Dokumentpost</h4>
    <dl class="row">
        @foreach ($schema->groups[1]->fields as $field)
            @if ($field->showInRecordView)
                <dt class="col-sm-3 text-sm-right text-monospace">{{ $field->label }}</dt>
                <dd class="col-sm-9 text-sm text-monospace">
                    @if ($field->key == 'har_hefter')
                        @if ($record->{$field->key} == '1')
                            <a href="{{ action('\App\Bases\Bibsys\Controller@index', ['f0' => 'seriedokid', 'v0' => '92pt00159']) }}">Vis hefter</a>
                        @else
                            <em>Nei</em>
                        @endif
                    @elseif ($field->key == 'seriedokid')
                        <a href="{{ action('\App\Bases\Bibsys\Controller@show', ['dokid' => $record->{$field->key}]) }}">{{ $record->{$field->key} }}</a>
                    @else
                        {{ $record->{$field->key} }}
                    @endif
                </dd>
            @endif
        @endforeach
    </dl>
@endsection
