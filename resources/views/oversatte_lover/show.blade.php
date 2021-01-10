@extends('base.show')

@section('record')

    <dl class="row">
        <dt class="col-sm-3 text-sm-right">
            {{ __('tittel') }}
        </dt>
        <dd class="col-sm-9">
            {{ $record->tittel }}
        </dd>
        <dt class="col-sm-3 text-sm-right">
            {{ __('sprak') }}
        </dt>
        <dd class="col-sm-9">
            {{ __('oversatte_lover.sprak_verdier.' . $record->sprak) }}
        </dd>
    </dl>

    <h3>Oversettelse av</h3>

    <a href="{{ $base->action('LovController@show', $record->lov->id) }}">{{ $record->lov->tittel }}</a>

@endsection
