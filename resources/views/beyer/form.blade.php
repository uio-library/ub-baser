    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Kritikken</h3>
        </div>

        <div class="panel-body">

            <div class="form-group">
                <label for="navn" class="col-sm-2 control-label">{{ trans('beyer.type') }}</label>
                <div class="col-sm-4">
                    {!! Form::select('kritikktype[]', $kritikktyper, old('kritikktype') ?: $record->kritikktype, ['id' => 'kritikktype', 'multiple' => 'multiple']) !!}
                </div>

                <label for="spraak" class="col-sm-2 control-label">{{ trans('beyer.spraak') }}</label>
                <div class="col-sm-4">
                    {!! Form::select('spraak', $spraakliste, old('spraak') ?: $record->spraak, ['id' => 'spraak', 'placeholder' => trans('messages.choose')]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="tittel" class="col-sm-2 control-label">{{ trans('beyer.tittel') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="tittel" name="tittel" value="{{ old('tittel') ?: $record->tittel }}">
                </div>
            </div>

            <div class="form-group">
                <label for="kommentar" class="col-sm-2 control-label">{{ trans('beyer.kommentar') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="kommentar" name="kommentar" value="{{ old('kommentar') ?: $record->kommentar }}">
                </div>
            </div>

            <div class="form-group">
                <label for="publikasjon" class="col-sm-2 control-label">{{ trans('beyer.publikasjon') }}</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="publikasjon" name="publikasjon" value="{{ old('publikasjon') ?: $record->publikasjon }}">
                </div>
                <label for="utgivelsessted" class="col-sm-1 control-label">{{ trans('beyer.utgivelsessted') }}</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" value="{{ old('utgivelsessted') ?: $record->utgivelsessted }}">
                </div>
                <label for="aar" class="col-sm-1 control-label">{{ trans('beyer.aar') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="aar" name="aar" value="{{ old('aar') ?: $record->aar }}">
                </div>
            </div>

            <div class="form-group">
                <label for="dato" class="col-sm-2 control-label">{{ trans('beyer.dato') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="dato" name="dato" value="{{ old('dato') ?: $record->dato }}">
                </div>
                <label for="aargang" class="col-sm-1 control-label">{{ trans('beyer.aargang') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="aargang" name="aargang" value="{{ old('aargang') ?: $record->aargang }}">
                </div>
                <label for="bind" class="col-sm-1 control-label">{{ trans('beyer.bind') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="bind" name="bind" value="{{ old('bind') ?: $record->bind }}">
                </div>
            </div>

            <div class="form-group">
                <label for="hefte" class="col-sm-2 control-label">{{ trans('beyer.hefte') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="hefte" name="hefte" value="{{ old('hefte') ?: $record->hefte }}">
                </div>
                <label for="nummer" class="col-sm-1 control-label">{{ trans('beyer.nummer') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="nummer" name="nummer" value="{{ old('nummer') ?: $record->nummer }}">
                </div>
                <label for="sidetall" class="col-sm-1 control-label">{{ trans('beyer.sidetall') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="sidetall" name="sidetall" value="{{ old('sidetall') ?: $record->sidetall }}">
                </div>
            </div>

            <div class="form-group">
                <label for="utgivelseskommentar" class="col-sm-2 control-label">{{ trans('beyer.utgivelseskommentar') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="utgivelseskommentar" name="utgivelseskommentar" value="{{ old('utgivelseskommentar') }}">
                </div>
            </div>

        </div>

            <ul class="list-group">
                <li class="list-group-item">
                <h4 class="list-group-item-heading">Kritikeren:</h4>

                <div class="form-group">
                    <label for="kritiker_etternavn" class="col-sm-2 control-label">{{ trans('beyer.kritiker_etternavn') }}</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="kritiker_etternavn" name="kritiker_etternavn" value="{{ old('kritiker_etternavn') ?: $record->kritiker_etternavn }}">
                    </div>
                    <label for="kritiker_fornavn" class="col-sm-2 control-label">{{ trans('beyer.kritiker_fornavn') }}</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="kritiker_fornavn" name="kritiker_fornavn" value="{{ old('kritiker_fornavn') ?: $record->kritiker_fornavn }}">
                    </div>
                    <div class="col-sm-2">
                        {!! Form::select('kjonn', $kjonnstyper, old('kjonn') ?: $record->kjonn, ['id' => 'kjonn', 'placeholder' => 'Kjønn']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="kritiker_pseudonym" class="col-sm-2 control-label">{{ trans('beyer.kritiker_pseudonym') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kritiker_pseudonym" name="kritiker_pseudonym" value="{{ old('kritiker_pseudonym') ?: $record->kritiker_pseudonym }}">
                    </div>
                    <label for="kritiker_kommentar" class="col-sm-2 control-label">{{ trans('beyer.kritiker_kommentar') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kritiker_kommentar" name="kritiker_kommentar" value="{{ old('kritiker_kommentar') ?: $record->kritiker_kommentar }}">
                    </div>
                </div>
                </li>
            </ul>

    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Det kritiserte verket</h3>
        </div>

        <ul class="list-group">

            <!-- Verket -->
            <li class="list-group-item">
                <h4 class="list-group-item-heading">Verket:</h4>

                <div class="form-group">
                    <label for="verk_tittel" class="col-sm-2 control-label">{{ trans('beyer.verk_tittel') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_tittel" name="verk_tittel" value="{{ old('verk_tittel') ?: $record->verk_tittel }}">
                    </div>
                    <label for="verk_aar" class="col-sm-1 control-label">{{ trans('beyer.verk_aar') }}</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="verk_aar" name="verk_aar" value="{{ old('verk_aar') ?: $record->verk_aar }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="verk_sjanger" class="col-sm-2 control-label">{{ trans('beyer.verk_sjanger') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_sjanger" name="verk_sjanger" value="{{ old('verk_sjanger') ?: $record->verk_sjanger }}">
                    </div>
                    <label for="verk_spraak" class="col-sm-1 control-label">{{ trans('beyer.verk_spraak') }}</label>
                    <div class="col-sm-5">
                        {!! Form::select('verk_spraak[]', $spraakliste, old('verk_spraak') ?: $record->verk_spraak, ['id' => 'verk_spraak', 'placeholder' =>  trans('messages.choose'), 'multiple' => 'multiple']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="verk_kommentar" class="col-sm-2 control-label">{{ trans('beyer.verk_kommentar') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_kommentar" name="verk_kommentar" value="{{ old('verk_kommentar') ?: $record->verk_kommentar }}">
                    </div>
                    <label for="verk_utgivelsessted" class="col-sm-2 control-label">{{ trans('beyer.verk_utgivelsessted') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_utgivelsessted" name="verk_utgivelsessted" value="{{ old('verk_utgivelsessted') ?: $record->verk_utgivelsessted }}">
                    </div>
                </div>
            </li>

            <!-- Forfatter -->
            <li class="list-group-item">
                <h4 class="list-group-item-heading">Forfatter:</h4>

                <div class="form-group">
                    <label for="kritiker_etternavn" class="col-sm-2 control-label">{{ trans('beyer.kritiker_etternavn') }}</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="kritiker_etternavn" name="kritiker_etternavn" value="{{ old('kritiker_etternavn') ?: $record->kritiker_etternavn }}">
                    </div>
                    <label for="kritiker_fornavn" class="col-sm-2 control-label">{{ trans('beyer.kritiker_fornavn') }}</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="kritiker_fornavn" name="kritiker_fornavn" value="{{ old('kritiker_fornavn') ?: $record->kritiker_fornavn }}">
                    </div>
                    <div class="col-sm-2">
                        {!! Form::select('kritiker_kjonn', $kjonnstyper, old('kritiker_kjonn') ?: $record->kritiker_kjonn, ['id' => 'kritiker_kjonn', 'placeholder' => 'Kjønn']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="kritiker_pseudonym" class="col-sm-2 control-label">{{ trans('beyer.kritiker_pseudonym') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kritiker_pseudonym" name="kritiker_pseudonym" value="{{ old('kritiker_pseudonym') ?: $record->kritiker_pseudonym }}">
                    </div>
                    <label for="kritiker_kommentar" class="col-sm-2 control-label">{{ trans('beyer.kritiker_kommentar') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kritiker_kommentar" name="kritiker_kommentar" value="{{ old('kritiker_kommentar') ?: $record->kritiker_kommentar }}">
                    </div>
                </div>
            </li>
        </ul>

    </div>


    @section('script')

        <script>
            $('#kritikktype').selectize({});
            $('#spraak').selectize({});
            $('#verk_spraak').selectize({});
            $('#kjonn').selectize({});
            $('#kritiker_kjonn').selectize({});
        </script>

    @endsection
