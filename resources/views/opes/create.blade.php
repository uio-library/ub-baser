@extends('opes.layout')

@section('content')

    <h2>
        Opprett ny post
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ action('\App\Bases\Opes\Controller@store') }}" class="form-horizontal">
        @csrf

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
