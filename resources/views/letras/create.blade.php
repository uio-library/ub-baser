@extends('layouts.letras')

@section('content')

    <h2>
        Opprett ny post
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ action('LetrasController@store') }}" class="form-horizontal">
        {!! csrf_field() !!}

        <edit-form
                :schema="{{ json_encode($schema) }}"
                :values="{{ json_encode($values) }}"
        ></edit-form>

        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('messages.create') }}</button>
            </div>
        </div>

    </form>

@endsection
