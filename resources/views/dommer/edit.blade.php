@extends('layouts.dommer')

@section('content')

        <h2>
            Rediger post #{{ $record->id }}
        </h2>

        @include('shared.errors')

        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title">Rediger post</h3>
            </div>

            <div class="panel-body">


                <form method="POST" action="{{ action('DommerController@update', $record->id) }}" class="form-horizontal">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT" />

                    <div class="form-group">
                        <label for="navn" class="col-sm-2 control-label">{{ trans('dommer.navn') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="navn" name="navn" value="{{ old('navn') ?: $record->navn }}">
                        </div>
                      </div>

                    <div class="form-group">
                        <label for="kilde_id" class="col-sm-2 control-label">{{ trans('dommer.kilde_navn') }}</label>
                        <div class="col-sm-4">
                            {!! Form::select('kilde_id', $kilder, old('kilde_id')  ?: $record->kilde_id, ['placeholder' => trans('messages.choose'), 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="aar">{{ trans('dommer.aar') }}</label>
                            <input type="number" class="form-control" id="aar" name="aar" placeholder="{{ trans('dommer.aar') }}" value="{{ old('aar')  ?: $record->aar }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="side">{{ trans('dommer.side') }}</label>
                            <input type="number" class="form-control" id="side" name="side" placeholder="{{ trans('dommer.side') }}" value="{{ old('side')  ?: $record->side }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">{{ trans('messages.save') }}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title">Slett post</h3>
            </div>

            <div class="panel-body">

                <form method="POST" action="{{ action('DommerController@destroy', $record->id) }}">
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



@endsection
