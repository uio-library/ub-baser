edit.blade.php

@extends('layouts.letras')

@section('content')

    <h2>
        Rediger post #{{ $record->id }}
    </h2>

    @include('shared.errors')

    <form method="POST" action="{{ action('LetrasController@update', $record->id) }}" class="form-horizontal">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PUT">

        <edit-form
                :schema="{{ json_encode($schema) }}"
                :values="{{ json_encode($values) }}"
        ></edit-form>

        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>
            </div>
        </div>

    </form>

    {{--

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Slett post</h3>
        </div>

        <div class="panel-body">

            <form method="POST" action="{{ action('LetrasController@destroy', $record->id) }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label>
                            <input type="checkbox" name="confirm-delete">
                            {{ trans('messages.confirm-delete') }}
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-danger">{{ trans('messages.deleterecord') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
    --}}

@endsection
