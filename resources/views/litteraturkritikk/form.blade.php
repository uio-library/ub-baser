    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Kritikken</h3>
        </div>

        <div class="panel-body">

            <div class="form-group">
                <label for="navn" class="col-sm-2 control-label">{{ trans('litteraturkritikk.type') }}</label>
                <div class="col-sm-4">
                    {!! Form::select('kritikktype[]', $kritikktyper, old('kritikktype') ?: $record->kritikktype, ['id' => 'kritikktype', 'multiple' => 'multiple']) !!}
                </div>

                <label for="spraak" class="col-sm-2 control-label">{{ trans('litteraturkritikk.spraak') }}</label>
                <div class="col-sm-4">
                    {!! Form::select('spraak', $spraakliste, old('spraak') ?: $record->spraak, ['id' => 'spraak', 'placeholder' => trans('messages.choose')]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="tittel" class="col-sm-2 control-label">{{ trans('litteraturkritikk.tittel') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="tittel" name="tittel" value="{{ old('tittel') ?: $record->tittel }}">
                </div>
            </div>

            <div class="form-group">
                <label for="publikasjon" class="col-sm-2 control-label">{{ trans('litteraturkritikk.publikasjon') }}</label>
                <div class="col-sm-3">
                    {!! Form::select('publikasjon', [], old('publikasjon') ?: $record->publikasjon, ['id' => 'publikasjon', 'placeholder' =>  trans('messages.choose')]) !!}

                </div>
                <label for="utgivelsessted" class="col-sm-1 control-label">{{ trans('litteraturkritikk.utgivelsessted') }}</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" value="{{ old('utgivelsessted') ?: $record->utgivelsessted }}">
                </div>
                <label for="aar" class="col-sm-1 control-label">{{ trans('litteraturkritikk.aar') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="aar" name="aar" value="{{ old('aar') ?: $record->aar }}">
                </div>
            </div>

            <div class="form-group">
                <label for="dato" class="col-sm-2 control-label">{{ trans('litteraturkritikk.dato') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="dato" name="dato" value="{{ old('dato') ?: $record->dato }}">
                </div>
                <label for="aargang" class="col-sm-1 control-label">{{ trans('litteraturkritikk.aargang') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="aargang" name="aargang" value="{{ old('aargang') ?: $record->aargang }}">
                </div>
                <label for="bind" class="col-sm-1 control-label">{{ trans('litteraturkritikk.bind') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="bind" name="bind" value="{{ old('bind') ?: $record->bind }}">
                </div>
            </div>

            <div class="form-group">
                <label for="hefte" class="col-sm-2 control-label">{{ trans('litteraturkritikk.hefte') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="hefte" name="hefte" value="{{ old('hefte') ?: $record->hefte }}">
                </div>
                <label for="nummer" class="col-sm-1 control-label">{{ trans('litteraturkritikk.nummer') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="nummer" name="nummer" value="{{ old('nummer') ?: $record->nummer }}">
                </div>
                <label for="sidetall" class="col-sm-1 control-label">{{ trans('litteraturkritikk.sidetall') }}</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="sidetall" name="sidetall" value="{{ old('sidetall') ?: $record->sidetall }}">
                </div>
            </div>

            <div class="form-group">
                <label for="kommentar" class="col-sm-2 control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                <div class="col-sm-10">
                    <input placeholder="Merknad om innholdet i kritikken." type="text" class="form-control" id="kommentar" name="kommentar" value="{{ old('kommentar') ?: $record->kommentar }}">
                </div>
            </div>

            <div class="form-group">
                <label for="utgivelseskommentar" class="col-sm-2 control-label">{{ trans('litteraturkritikk.utgivelseskommentar') }}</label>
                <div class="col-sm-10">
                    <input placeholder="Merknad om utgivelsen (f.eks. 'innledning', 'egenpublisert', usikker info)." type="text" class="form-control" id="utgivelseskommentar" name="utgivelseskommentar" value="{{ old('utgivelseskommentar') }}">
                </div>
            </div>

        </div>

            <ul class="list-group">
                <li class="list-group-item">
                <h4 class="list-group-item-heading">Forfatter(e) av kritikken:</h4>

                    <div class="control-group">
                        <label for="kritikere" class="sr-only">Personer:</label>
                        <select id="kritikere" name="kritikere[]" placeholder="Etternavn, Fornavn" multiple>
                            @foreach (old('kritikere') ?: $record->kritikere as $person)
                            <option value="{{ strval($person) }}" selected>{{ strval($person) }}</option>
                            @endforeach
                        </select>
                        <label>
                            {!! Form::checkbox('kritiker_mfl', 'kritiker_mfl', $record->kritiker_mfl) !!}
                            m. fl.
                        </label>
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
                    <label for="verk_tittel" class="col-sm-2 control-label">{{ trans('litteraturkritikk.tittel') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_tittel" name="verk_tittel" value="{{ old('verk_tittel') ?: $record->verk_tittel }}">
                    </div>
                    <label for="verk_aar" class="col-sm-1 control-label">{{ trans('litteraturkritikk.aar') }}</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="verk_aar" name="verk_aar" value="{{ old('verk_aar') ?: $record->verk_aar }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="verk_sjanger" class="col-sm-2 control-label">{{ trans('litteraturkritikk.sjanger') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_sjanger" name="verk_sjanger" value="{{ old('verk_sjanger') ?: $record->verk_sjanger }}">
                    </div>
                    <label for="verk_spraak" class="col-sm-1 control-label">{{ trans('litteraturkritikk.spraak') }}</label>
                    <div class="col-sm-4">
                        {!! Form::select('verk_spraak[]', $spraakliste, old('verk_spraak') ?: $record->verk_spraak, ['id' => 'verk_spraak', 'placeholder' =>  trans('messages.choose'), 'multiple' => 'multiple']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="verk_kommentar" class="col-sm-2 control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_kommentar" name="verk_kommentar" value="{{ old('verk_kommentar') ?: $record->verk_kommentar }}">
                    </div>
                    <label for="verk_utgivelsessted" class="col-sm-1 control-label">{{ trans('litteraturkritikk.utgivelsessted') }}</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="verk_utgivelsessted" name="verk_utgivelsessted" value="{{ old('verk_utgivelsessted') ?: $record->verk_utgivelsessted }}">
                    </div>
                </div>
            </li>

            <!-- Forfatter -->
            <li class="list-group-item">
                <h4 class="list-group-item-heading">Forfatter(e) av det kritiserte verket:</h4>

                <div class="control-group">

                    <label for="forfattere" class="sr-only">Personer:</label>
                    <select id="forfattere" name="forfattere[]" placeholder="Etternavn, Fornavn" multiple>
                    @foreach ( old('forfattere') ?: $record->forfattere as $person)
                        <option value="{{ strval($person) }}" selected>{{ strval($person) }}</option>
                    @endforeach
                    </select>

                    <label>
                        {!! Form::checkbox('is_edited', 'is_edited', $is_edited) !!}
                        Redaktør
                    </label>
                    <label>
                        {!! Form::checkbox('forfatter_mfl', 'forfatter_mfl', $record->forfatter_mfl) !!}
                        m. fl.
                    </label>

                </div>

            </li>
        </ul>

    </div>

    @section('script')

        <script>

            var defaultOptions = {
                openOnFocus: false,
                closeAfterSelect: true,
                selectOnTab: true
            };

            $('#kritikktype').selectize(defaultOptions);
            $('#spraak').selectize(defaultOptions);
            $('#verk_spraak').selectize(defaultOptions);

            function search(field, query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ action("LitteraturkritikkController@search") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        field: field,
                        q: query
                    },
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            }

            var selectPersonsOptions = {
                valueField: 'value',
                labelField: 'value',
                searchField: 'value',
                options: [],
                delimiter: null,
                create: true,
                selectOnTab: true,
                openOnFocus: false,
                closeAfterSelect: true,
                load: function(query, callback) {
                    search('person', query, callback);
                }
            };

            $('#forfattere').selectize(selectPersonsOptions);
            $('#kritikere').selectize(selectPersonsOptions);

            var selectPublicationOptions = {
                valueField: 'value',
                labelField: 'value',
                searchField: 'value',
                options: [],
                delimiter: null,
                create: true,
                selectOnTab: true,
                openOnFocus: false,
                closeAfterSelect: true,
                load: function(query, callback) {
                    search('publikasjon', query, callback);
                }
            };

            $('#publikasjon').selectize(selectPublicationOptions);

        </script>

    @endsection
