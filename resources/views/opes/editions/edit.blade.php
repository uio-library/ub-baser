@extends('opes.layout')

@section('content')

    <h2>
        Edit publication
    </h2>

    @include('shared.errors')

    <p>
        Topic:
        <a href="{{ $base->action('show', $record->record->id) }}">{{ $record->recordView }}</a>
    </p>

    <edit-form
        method="PUT"
        action="{{ $base->action('update', $record->id) }}"
        csrf-token="{{ csrf_token() }}"
        :schema="{{ json_encode($schema) }}"
        :settings="{{ json_encode($settings) }}"
        :values="{{ json_encode($values) }}"
    ></edit-form>

@endsection
