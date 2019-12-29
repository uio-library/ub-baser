@extends('layout')

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action('Admin\BaseController@index') }}">{{ trans('messages.managebases') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $base->title }}</li>
        </ol>
    </nav>

    @include('shared.errors')

    <div class="card my-3">

        <div class="card-header">
            Rediger base
        </div>

        <div class="card-body">

            <form method="POST" action="{{ action('Admin\BaseController@update', $base->id) }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <label for="staticId" class="col-sm-2 col-form-label">{{ trans('base.id') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticId" value="{{ $base->id }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticBasepath" class="col-sm-2 col-form-label">{{ trans('base.basepath') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticBasepath" value="{{ $base->basepath }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticNamespace" class="col-sm-2 col-form-label">{{ trans('base.namespace') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticNamespace" value="{{ $base->namespace }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticLanguages" class="col-sm-2 col-form-label">{{ trans('base.languages') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticLanguages" value="{{ implode(', ', $base->languages) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticDefaultLanguage" class="col-sm-2 col-form-label">{{ trans('base.default_language') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticDefaultLanguage" value="{{ $base->default_language }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticDefaultLanguage" class="col-sm-2 col-form-label">{{ trans('base.default_language') }}</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticDefaultLanguage" value="{{ $base->default_language }}">
                    </div>
                </div>
{{--
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">{{ trans('messages.save') }}</button>
                    </div>
                </div>
--}}

            </form>

        </div>
    </div>

@endsection
