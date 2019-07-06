    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Kritikken</h3>
        </div>

        <div class="panel-body">

            <div class="form-group">

                <div class="col-sm-7">
                    <label for="tittel" class="control-label">{{ trans('litteraturkritikk.tittel') }}</label>
                    <input type="text" class="form-control" id="tittel" name="tittel" value="{{ old('tittel') ?: $record->tittel }}">
                </div>

                <div class="col-sm-3">
                    <label for="navn" class="control-label">{{ trans('litteraturkritikk.type') }}</label>
                    {!! Form::select('kritikktype[]', $kritikktyper, old('kritikktype') ?: $record->kritikktype, ['id' => 'kritikktype', 'multiple' => 'multiple']) !!}
                </div>

                <div class="col-sm-2">
                    <label for="spraak" class="control-label">{{ trans('litteraturkritikk.spraak') }}</label>
                    {!! Form::select('spraak', $spraakliste, old('spraak') ?: $record->spraak, ['id' => 'spraak', 'placeholder' => trans('messages.choose')]) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-5">
                <label for="publikasjon" class="control-label">{{ trans('litteraturkritikk.publikasjon') }}</label>
                    <select id="publikasjon" name="publikasjon" placeholder="{{ trans('messages.choose') }}">
                        @if (old('publikasjon') ?: $record->publikasjon)
                            <option value="{{ old('publikasjon') ?: $record->publikasjon }}" selected>{{ old('publikasjon') ?: $record->publikasjon }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-sm-4">
                <label for="utgivelsessted" class="control-label">{{ trans('litteraturkritikk.utgivelsessted') }}</label>

                    <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" value="{{ old('utgivelsessted') ?: $record->utgivelsessted }}">
                </div>
                <div class="col-sm-3">
                    <label for="aar" class="control-label">{{ trans('litteraturkritikk.aar') }}</label>
                    <input type="text" class="form-control" id="aar" name="aar" value="{{ old('aar') ?: $record->aar }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="dato" class="control-label">{{ trans('litteraturkritikk.dato') }}</label>
                    <input type="text" class="form-control" id="dato" name="dato" value="{{ old('dato') ?: $record->dato }}">
                </div>
                <div class="col-sm-2">
                    <label for="aargang" class="control-label">{{ trans('litteraturkritikk.aargang') }}</label>
                    <input type="text" class="form-control" id="aargang" name="aargang" value="{{ old('aargang') ?: $record->aargang }}">
                </div>
                <div class="col-sm-2">
                    <label for="bind" class="control-label">{{ trans('litteraturkritikk.bind') }}</label>
                    <input type="text" class="form-control" id="bind" name="bind" value="{{ old('bind') ?: $record->bind }}">
                </div>
                <div class="col-sm-2">
                    <label for="hefte" class="control-label">{{ trans('litteraturkritikk.hefte') }}</label>
                    <input type="text" class="form-control" id="hefte" name="hefte" value="{{ old('hefte') ?: $record->hefte }}">
                </div>
                <div class="col-sm-2">
                    <label for="nummer" class="control-label">{{ trans('litteraturkritikk.nummer') }}</label>
                    <input type="text" class="form-control" id="nummer" name="nummer" value="{{ old('nummer') ?: $record->nummer }}">
                </div>
                <div class="col-sm-2">
                    <label for="sidetall" class="control-label">{{ trans('litteraturkritikk.sidetall') }}</label>
                    <input type="text" class="form-control" id="sidetall" name="sidetall" value="{{ old('sidetall') ?: $record->sidetall }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label for="kommentar" class="control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                    <input placeholder="Merknad om innholdet i kritikken." type="text" class="form-control" id="kommentar" name="kommentar" value="{{ old('kommentar') ?: $record->kommentar }}">
                </div>
                <div class="col-sm-6">
                    <label for="utgivelseskommentar" class="control-label">{{ trans('litteraturkritikk.utgivelseskommentar') }}</label>
                    <input placeholder="Merknad om utgivelsen (f.eks. 'innledning', 'egenpublisert', usikker info)." type="text" class="form-control" id="utgivelseskommentar" name="utgivelseskommentar" value="{{ old('utgivelseskommentar') }}">
                </div>
            </div>

        </div>

            <ul class="list-group">
                <li class="list-group-item">

                    <div class="form-group">

                        <div class="col-sm-4">

                            <label for="kritikere">Forfatter(e):</label>
                            <select id="kritikere" name="kritikere[]" placeholder="Etternavn, Fornavn" multiple>
                            @foreach ( old('kritikere') ?: $record->kritikere as $person)
                                <option value="{{ strval($person) }}" selected>{{ strval($person) }}</option>
                            @endforeach
                            </select>

                            <label>
                                {!! Form::checkbox('kritiker_mfl', 'kritiker_mfl', $record->kritiker_mfl) !!}
                                m. fl.
                            </label>

                        </div>

                        <div class="col-sm-4">

                            <label for="kritiker_kommentar">Merknad om forfatterskap:</label>
                            <input type="text" class="form-control" id="kritiker_kommentar" name="kritiker_kommentar" value="{{ old('kritiker_kommentar') ?: $record->kritiker_kommentar }}">

                        </div>

                    </div>

                </li>
            </ul>

    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Kritisert/omtalt</h3>
        </div>

        <ul class="list-group">

            <!-- Verket -->
            <li class="list-group-item">

                <div class="form-group">
                    <div class="col-sm-4">
                        <label for="verk_tittel" class="control-label">{{ trans('litteraturkritikk.tittel') }}</label>
                        <input type="text" class="form-control" id="verk_tittel" name="verk_tittel" value="{{ old('verk_tittel') ?: $record->verk_tittel }}">
                    </div>

                    <div class="col-sm-3">
                        <label for="verk_sjanger" class="control-label">{{ trans('litteraturkritikk.sjanger') }}</label>
                        <input type="text" class="form-control" id="verk_sjanger" name="verk_sjanger" value="{{ old('verk_sjanger') ?: $record->verk_sjanger }}">
                    </div>

                    <div class="col-sm-2">
                        <label for="verk_aar" class="control-label">{{ trans('litteraturkritikk.aar') }}</label>
                        <input type="text" class="form-control" id="verk_aar" name="verk_aar" value="{{ old('verk_aar') ?: $record->verk_aar }}">
                    </div>

                    <div class="col-sm-3">
                        <label for="verk_spraak" class="control-label">{{ trans('litteraturkritikk.spraak') }}</label>
                        {!! Form::select('verk_spraak[]', $spraakliste, old('verk_spraak') ?: $record->verk_spraak, ['id' => 'verk_spraak', 'placeholder' =>  trans('messages.choose'), 'multiple' => 'multiple']) !!}
                    </div>

                </div>

                <div class="form-group">

                    <div class="col-sm-5">
                        <label for="verk_kommentar" class="control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                        <input type="text" class="form-control" id="verk_kommentar" name="verk_kommentar" value="{{ old('verk_kommentar') ?: $record->verk_kommentar }}">
                    </div>
                    <div class="col-sm-4">
                        <label for="verk_utgivelsessted" class="control-label">{{ trans('litteraturkritikk.utgivelsessted') }}</label>
                        <input type="text" class="form-control" id="verk_utgivelsessted" name="verk_utgivelsessted" value="{{ old('verk_utgivelsessted') ?: $record->verk_utgivelsessted }}">
                    </div>
                </div>
            </li>

            <!-- Forfatter -->
            <li class="list-group-item">

                <div class="form-group">

                    <div class="col-sm-4">

                        <label for="forfattere">Forfatter(e):</label>
                        <select id="forfattere" name="forfattere[]" placeholder="Etternavn, Fornavn" multiple>
                        @foreach ( old('forfattere') ?: $record->forfattere as $person)
                            <option value="{{ strval($person) }}" selected>{{ strval($person) }}</option>
                        @endforeach
                        </select>

                        <label>
                            {!! Form::checkbox('forfatter_mfl', 'forfatter_mfl', $record->forfatter_mfl) !!}
                            m. fl.
                        </label>

                    </div>

                    <div class="col-sm-4">
                        <label for="person_role">Rolle:</label>
                        <input type="text" class="form-control" id="person_role" name="person_role" placeholder="Hvis annen en forfatter. Eks.: red., utgiver" value="{{ old('person_role') ?: $person_role }}">
                    </div>

                    <div class="col-sm-4">

                        <label for="forfatter_kommentar">Merknad om forfatterskap:</label>
                        <input type="text" class="form-control" id="forfatter_kommentar" name="forfatter_kommentar" value="{{ old('forfatter_kommentar') ?: $record->forfatter_kommentar }}">

                    </div>
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
                    url: '{{ action("LitteraturkritikkController@autocomplete") }}',
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
