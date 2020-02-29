@extends('base.show')

@section('record')
    <dl class="row">
        @foreach ($schema->fields as $field)
            @if ($field->displayable && !empty($record->{$field->key}))
                <dt class="col-sm-3 text-sm-right">
                    {{ $field->label }}
                </dt>
                <dd class="col-sm-9">
                    {{ $record->{$field->key} }}
                </dd>
            @endif
        @endforeach
    </dl>
@endsection
