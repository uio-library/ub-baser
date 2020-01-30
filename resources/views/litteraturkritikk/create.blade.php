@extends('litteraturkritikk.layout')

@section('content')

    <p style="float:right">
        <a href="{{ $base->pageAction('veiledning') }}" target="veiledning">Redigeringsveiledning</a> (åpner i nytt vindu/ny fane)
    </p>

    <h2>
        Opprett ny post
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ $base->action('store') }}" class="form-horizontal">
        {!! csrf_field() !!}

        <edit-form
            :schema="{{ json_encode($schema) }}"
            :settings="{{ json_encode($settings) }}"
            :values="{{ json_encode($values) }}"
        ></edit-form>

        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('messages.create') }}</button>
            </div>
        </div>

    </form>

@endsection
