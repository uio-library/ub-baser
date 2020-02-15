@extends('litteraturkritikk.layout')

@section('content')

    <h2>Slett person {{ $record->id }}</h2>

    @include('shared.errors')

    <div class="panel panel-default">

        <div class="panel-body">

            <p>
                Merk: Ved sletting av en person blir alle tilknyttede poster frakoblet personen.
                Postene selv blir ikke slettet.
            </p>

            <form method="POST" action="{{ $base->action('PersonController@destroy', $record->id) }}">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label>
                            <input type="checkbox" name="confirm-deleteperson">
                            {{ trans('litteraturkritikk.confirm-deleteperson') }}
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-danger">{{ trans('litteraturkritikk.deleteperson') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
