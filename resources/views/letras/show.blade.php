@extends('base.show')

@section('record')

    @foreach ($schema->groups as $group)
        <h4 class="mt-4">{{ $group->label }}</h4>
        <dl class="row">
            @foreach ($group->fields as $field)
                @if ($field->displayable)
                    <dt class="col-sm-3 text-sm-right">
                        {{ $field->label }}
                    </dt>
                    <dd class="col-sm-9">
                        {{ $record->{$field->key} }}
                    </dd>
                @endif
            @endforeach
        </dl>
    @endforeach

@endsection
