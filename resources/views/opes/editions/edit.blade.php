@extends('opes.layout')

@section('content')

    <h2>
        Edit edition
    </h2>

    @include('shared.errors')

    @if ($record->record)
        <p>
        Belongs to record:
        <a href="{{ $base->action('show', $record->record->id) }}">{{ $record->recordView }}</a>
        </p>
    @endif

    <edit-form
        method="PUT"
        action="{{ $base->action('EditionController@update', $record->id) }}"
        csrf-token="{{ csrf_token() }}"
        :schema="{{ json_encode($schema) }}"
        :settings="{{ json_encode($settings) }}"
        :values="{{ json_encode($values) }}"
    ></edit-form>

@endsection
