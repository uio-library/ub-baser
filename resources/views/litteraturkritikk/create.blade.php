@extends('litteraturkritikk.layout')

@section('content')

    <p style="float:right">
        <a href="{{ $base->pageAction('veiledning') }}" target="veiledning">Redigeringsveiledning</a> (åpner i nytt vindu/ny fane)
    </p>

    <h2>
        Opprett ny post
    </h2>

    @include('shared.errors')

    <edit-form
        method="POST"
        action="{{ $base->action('store') }}"
        csrf-token="{{ csrf_token() }}"
        :schema="{{ json_encode($schema) }}"
        :settings="{{ json_encode($settings) }}"
        :values="{{ json_encode($values) }}"
    ></edit-form>

@endsection
