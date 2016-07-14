@extends('layouts.litteraturkritikk')

@section('content')

    <h2>Person {{ $person->id }}</h2>

    @include('shared.errors')

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Rediger person</h3>
        </div>

        <div class="panel-body">

            <form method="POST" action="{{ action('LitteraturkritikkPersonController@update', $person->id) }}">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">

                <div class="form-group row">
                    <label for="etternavnInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.etternavn') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="etternavnInput" name="etternavn" value="{{ old('etternavn') ?: $person->etternavn }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fornavnInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.fornavn') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="fornavnInput" name="fornavn" value="{{ old('fornavn') ?: $person->fornavn }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kjonnInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.kjonn') }}</label>
                    <div class="col-sm-10">
                        {!! Form::select('kjonn', $kjonnstyper, old('kjonn') ?: $person->kjonn, ['id' => 'kjonn', 'placeholder' => 'Kjønn']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pseudonymInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.pseudonym') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pseudonymInput" name="pseudonym" value="{{ old('pseudonym') ?: $person->pseudonym }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pseudonymForInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.pseudonym_for') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pseudonymForInput" name="pseudonym_for" value="{{ old('pseudonym_for') ?: $person->pseudonym_for }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kommentarInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kommentarInput" name="kommentar" value="{{ old('kommentar') ?: $person->kommentar }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="bibsysIdInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.bibsys_id') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="bibsysIdInput" name="bibsys_id" value="{{ old('bibsys_id') ?: $person->bibsys_id }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="birthYearInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.birth_year') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="birthYearInput" name="birth_year" value="{{ old('birth_year') ?: $person->birth_year }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="deathYearInput" class="col-sm-2 form-control-label">{{ trans('litteraturkritikk.death_year') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="deathYearInput" name="death_year" value="{{ old('death_year') ?: $person->death_year }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">{{ trans('messages.save') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Slett person</h3>
        </div>

        <div class="panel-body">

            <p>
                Merk: Ved sletting av en person blir alle tilknyttede poster frakoblet personen.
                Postene selv blir ikke slettet.
            </p>

            <form method="POST" action="{{ action('LitteraturkritikkPersonController@destroy', $person->id) }}">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label>
                            <input type="checkbox" name="confirm-deleteperson">
                            {{ trans('messages.confirm-deleteperson') }}
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-danger">{{ trans('messages.deleteperson') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
