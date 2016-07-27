create.blade.php

@extends('layouts.letras')

@section('content')

        <h2>
            Opprett ny post
        </h2>

        @include('shared.errors')

        <div class="panel panel-default">
            <div class="panel-body">

                <form method="POST" action="{{ action('LetrasController@store') }}" class="form-horizontal">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="forfatter" class="col-sm-2 control-label">{{ trans('letras.forfatter') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="forfatter" name="forfatter" value="{{ old('forfatter') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="land">{{ trans('letras.land') }}</label>
                            <input type="text" class="form-control" id="land" name="land" placeholder="{{ trans('letras.land') }}" value="{{ old('land') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="tittel">{{ trans('letras.tittel') }}</label>
                            <input type="text" class="form-control" id="tittel" name="tittel" placeholder="{{ trans('letras.tittel') }}" value="{{ old('tittel') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsesaar">{{ trans('letras.utgivelsesaar') }}</label>
                            <input type="text" class="form-control" id="utgivelsesaar" name="utgivelsesaar" placeholder="{{ trans('letras.utgivelsesaar') }}" value="{{ old('utgivelsesaar') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="sjanger">{{ trans('letras.sjanger') }}</label>
                            <input type="text" class="form-control" id="sjanger" name="sjanger" placeholder="{{ trans('letras.sjanger') }}" value="{{ old('sjanger') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="oversetter">{{ trans('letras.oversetter') }}</label>
                            <input type="text" class="form-control" id="oversetter" name="oversetter" placeholder="{{ trans('letras.oversetter') }}" value="{{ old('oversetter') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="tittel2">{{ trans('letras.tittel2') }}</label>
                            <input type="text" class="form-control" id="tittel2" name="tittel2" placeholder="{{ trans('letras.tittel2') }}" value="{{ old('tittel2') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsessted">{{ trans('letras.utgivelsessted') }}</label>
                            <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" placeholder="{{ trans('letras.utgivelsessted') }}" value="{{ old('utgivelsessted') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="utgivelsesaar2">{{ trans('letras.utgivelsesaar2') }}</label>
                            <input type="text" class="form-control" id="utgivelsesaar2" name="utgivelsesaar2" placeholder="{{ trans('letras.utgivelsesaar2') }}" value="{{ old('utgivelsesaar2') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="forlag">{{ trans('letras.forlag') }}</label>
                            <input type="text" class="form-control" id="forlag" name="forlag" placeholder="{{ trans('letras.forlag') }}" value="{{ old('forlag') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="foretterord">{{ trans('letras.foretterord') }}</label>
                            <input type="text" class="form-control" id="foretterord" name="foretterord" placeholder="{{ trans('letras.foretterord') }}" value="{{ old('foretterord') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="spraak">{{ trans('letras.spraak') }}</label>
                            <input type="text" class="form-control" id="spraak" name="spraak" placeholder="{{ trans('letras.spraak') }}" value="{{ old('spraak') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">{{ trans('messages.create') }}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>


@endsection
