@extends('layouts.litteraturkritikk')

@section('content')

    <h2>
        Rediger post {{ $record->id }}
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ action('LitteraturkritikkController@update', $record->id) }}" class="form-horizontal">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PUT">

        <litteraturkritikk-edit-form
                :columns="{{ json_encode($columns) }}"
                :labels="{{ json_encode(trans('litteraturkritikk')) }}"
                :values="{{ json_encode($values) }}"
        ></litteraturkritikk-edit-form>

        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>
            </div>
        </div>

    </form>

    <div style="height: 100px;">
        <!-- Some spacing for menus -->
    </div>

@endsection
