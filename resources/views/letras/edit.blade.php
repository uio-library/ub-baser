edit.blade.php

@extends('layouts.letras')

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


                <form method="POST" action="{{ action('LetrasController@update', $record->id) }}" class="form-horizontal">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT" />

                    <div class="form-group">
                        <label for="forfatter" class="col-sm-2 control-label">{{ trans('letras.forfatter') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="forfatter" name="forfatter" value="{{ old('forfatter') ?: $record->forfatter }}">
                        </div>
                      </div>

                    <div class="form-group">
                       
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="land">{{ trans('letras.land') }}</label>
                            <input type="text" class="form-control" id="land" name="land" placeholder="{{ trans('letras.land') }}" value="{{ old('land')  ?: $record->land }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="tittel">{{ trans('letras.tittel') }}</label>
                            <input type="text" class="form-control" id="tittel" name="tittel" placeholder="{{ trans('letras.tittel') }}" value="{{ old('tittel')  ?: $record->tittel }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsesaar">{{ trans('letras.utgivelsesaar') }}</label>
                            <input type="text" class="form-control" id="utgivelsesaar" name="utgivelsesaar" placeholder="{{ trans('letras.utgivelsesaar') }}" value="{{ old('utgivelsesaar')  ?: $record->utgivelsesaar }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="sjanger">{{ trans('letras.sjanger') }}</label>
                            <input type="text" class="form-control" id="sjanger" name="sjanger" placeholder="{{ trans('letras.sjanger') }}" value="{{ old('sjanger')  ?: $record->sjanger }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="oversetter">{{ trans('letras.oversetter') }}</label>
                            <input type="text" class="form-control" id="oversetter" name="oversetter" placeholder="{{ trans('letras.oversetter') }}" value="{{ old('oversetter')  ?: $record->oversetter }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="tittel2">{{ trans('letras.tittel2') }}</label>
                            <input type="text" class="form-control" id="tittel2" name="tittel2" placeholder="{{ trans('letras.tittel2') }}" value="{{ old('tittel2')  ?: $record->tittel2 }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsessted">{{ trans('letras.utgivelsessted') }}</label>
                            <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" placeholder="{{ trans('letras.utgivelsessted') }}" value="{{ old('utgivelsessted')  ?: $record->utgivelsessted }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsesaar2">{{ trans('letras.utgivelsesaar2') }}</label>
                            <input type="text" class="form-control" id="utgivelsesaar2" name="utgivelsesaar2" placeholder="{{ trans('letras.utgivelsesaar2') }}" value="{{ old('utgivelsesaar2')  ?: $record->utgivelsesaar2 }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="forlag">{{ trans('letras.forlag') }}</label>
                            <input type="text" class="form-control" id="forlag" name="forlag" placeholder="{{ trans('letras.forlag') }}" value="{{ old('forlag')  ?: $record->forlag }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="foretterord">{{ trans('letras.foretterord') }}</label>
                            <input type="text" class="form-control" id="foretterord" name="foretterord" placeholder="{{ trans('letras.foretterord') }}" value="{{ old('foretterord')  ?: $record->foretterord }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="spraak">{{ trans('letras.spraak') }}</label>
                            <input type="text" class="form-control" id="spraak" name="spraak" placeholder="{{ trans('letras.spraak') }}" value="{{ old('spraak')  ?: $record->spraak }}">
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



@endsection
