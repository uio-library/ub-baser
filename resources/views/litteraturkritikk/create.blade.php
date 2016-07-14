@extends('layouts.litteraturkritikk')

@section('content')

    <h2>
        Opprett ny post
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ action('LitteraturkritikkController@store') }}" class="form-horizontal">
        {!! csrf_field() !!}

        @include('litteraturkritikk.form')

        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('messages.create') }}</button>
            </div>
        </div>

    </form>

@endsection
