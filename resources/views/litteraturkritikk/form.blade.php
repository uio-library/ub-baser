
<div>

    <litteraturkritikk-edit-form
            :columns="{{ json_encode($columns) }}"
            :labels="{{ json_encode(trans('litteraturkritikk')) }}"
            :values="{{ json_encode($values) }}"
    ></litteraturkritikk-edit-form>

<!--
        <ul class="list-group">

            <li class="list-group-item">

                <div class="form-group">
                    <div class="col-sm-4">
                        <label for="verk_tittel" class="control-label">{{ trans('litteraturkritikk.verk_tittel') }}</label>
                        <input type="text" class="form-control" id="verk_tittel" name="verk_tittel" value="{{ old('verk_tittel', $record->verk_tittel) }}">
                    </div>

                    <div class="col-sm-4">
                        <label for="verk_utgivelsessted" class="control-label">{{ trans('litteraturkritikk.verk_utgivelsessted') }}</label>
                        <input type="text" class="form-control" id="verk_utgivelsessted" name="verk_utgivelsessted" value="{{   old('verk_utgivelsessted', $record->verk_utgivelsessted) }}">
                    </div>

                    <div class="col-sm-4">
                        <label for="verk_aar" class="control-label">{{ trans('litteraturkritikk.verk_aar') }}</label>
                        <input type="text" class="form-control" id="verk_aar" name="verk_aar" value="{{ old('verk_aar', $record->verk_aar) }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <label for="verk_sjanger" class="control-label">{{ trans('litteraturkritikk.verk_sjanger') }}</label>
                        <input type="text" class="form-control" id="verk_sjanger" name="verk_sjanger" value="{{ old('verk_sjanger', $record->verk_sjanger) }}">
                    </div>

                    <div class="col-sm-3">
                        <label for="verk_spraak" class="control-label">{{ trans('litteraturkritikk.verk_spraak') }}</label>
                        <input type="text"
                               class="form-control"
                               autocomplete="off"
                               id="verk_spraak"
                               name="verk_spraak"
                               value="{{ old('verk_spraak', $record->verk_spraak) }}"
                        >
                    </div>

                    <div class="col-sm-5">
                        <label for="verk_kommentar" class="control-label">{{ trans('litteraturkritikk.verk_kommentar') }}</label>
                        <input type="text" class="form-control" id="verk_kommentar" name="verk_kommentar" value="{{ old('verk_kommentar', $record->verk_kommentar) }}">
                    </div>
                </div>
            </li>

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
                            mfl.
                        </label>

                    </div>

                    <div class="col-sm-4">
                        <label for="person_role">Rolle:</label>
                        <input type="text"
                               class="form-control"
                               autocomplete="off"
                               id="person_role"
                               name="person_role"
                               placeholder="Hvis annen en forfatter. Eks.: red., utgiver"
                               value="{{ old('person_role', $record->person_role) }}"
                        >
                    </div>

                    <div class="col-sm-4">

                        <label for="forfatter_kommentar">Merknad om forfatterskap:</label>
                        <input type="text" class="form-control" id="forfatter_kommentar" name="forfatter_kommentar" value="{{ old('forfatter_kommentar', $record->forfatter_kommentar) }}">

                    </div>
                </div>

            </li>
        </ul>

    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Kritikken</h3>
        </div>

        <div class="panel-body">

            <div class="form-group">

                <div class="col-sm-7">
                    <label for="tittel" class="control-label">{{ trans('litteraturkritikk.tittel') }}</label>
                    <input type="text" class="form-control" id="tittel" name="tittel" value="{{ old('tittel', $record->tittel) }}">
                </div>

                <div class="col-sm-3">
                    <label for="navn" class="control-label">{{ trans('litteraturkritikk.type') }}</label>
                    {!! Form::select('kritikktype[]', $typeliste, old('kritikktype', $record->kritikktype), ['id' => 'kritikktype', 'multiple' => 'multiple']) !!}
                </div>

                <div class="col-sm-2">
                    <label for="spraak" class="control-label">{{ trans('litteraturkritikk.spraak') }}</label>
                    <input type="text" class="form-control" id="spraak" name="spraak" value="{{ old('spraak', $record->spraak) }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-5">
                    <label for="publikasjon" class="control-label">{{ trans('litteraturkritikk.publikasjon') }}</label>
                    <input type="text"
                           class="form-control"
                           autocomplete="off"
                           id="publikasjon"
                           name="publikasjon"
                           value="{{ old('publikasjon', $record->publikasjon) }}"
                    >
                </div>
                <div class="col-sm-4">
                <label for="utgivelsessted" class="control-label">{{ trans('litteraturkritikk.utgivelsessted') }}</label>

                    <input type="text" class="form-control" id="utgivelsessted" name="utgivelsessted" value="{{ old('utgivelsessted', $record->utgivelsessted) }}">
                </div>
                <div class="col-sm-3">
                    <label for="aar" class="control-label">{{ trans('litteraturkritikk.aar') }}</label>
                    <input type="text" class="form-control" id="aar" name="aar" value="{{ old('aar', $record->aar) }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="dato" class="control-label">{{ trans('litteraturkritikk.dato') }}</label>
                    <input type="text" class="form-control" id="dato" name="dato" value="{{ old('dato', $record->dato) }}">
                </div>
                <div class="col-sm-2">
                    <label for="aargang" class="control-label">{{ trans('litteraturkritikk.aargang') }}</label>
                    <input type="text" class="form-control" id="aargang" name="aargang" value="{{ old('aargang', $record->aargang) }}">
                </div>
                <div class="col-sm-2">
                    <label for="bind" class="control-label">{{ trans('litteraturkritikk.bind') }}</label>
                    <input type="text" class="form-control" id="bind" name="bind" value="{{ old('bind', $record->bind) }}">
                </div>
                <div class="col-sm-2">
                    <label for="hefte" class="control-label">{{ trans('litteraturkritikk.hefte') }}</label>
                    <input type="text" class="form-control" id="hefte" name="hefte" value="{{ old('hefte', $record->hefte) }}">
                </div>
                <div class="col-sm-2">
                    <label for="nummer" class="control-label">{{ trans('litteraturkritikk.nummer') }}</label>
                    <input type="text" class="form-control" id="nummer" name="nummer" value="{{ old('nummer', $record->nummer) }}">
                </div>
                <div class="col-sm-2">
                    <label for="sidetall" class="control-label">{{ trans('litteraturkritikk.sidetall') }}</label>
                    <input type="text" class="form-control" id="sidetall" name="sidetall" value="{{ old('sidetall', $record->sidetall) }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label for="kommentar" class="control-label">{{ trans('litteraturkritikk.kommentar') }}</label>
                    <input placeholder="Merknad om innholdet i kritikken." type="text" class="form-control" id="kommentar" name="kommentar" value="{{ old('kommentar', $record->kommentar) }}">
                </div>
                <div class="col-sm-6">
                    <label for="utgivelseskommentar" class="control-label">{{ trans('litteraturkritikk.utgivelseskommentar') }}</label>
                    <input placeholder="Merknad om utgivelsen (f.eks. 'innledning', 'egenpublisert', usikker info)." type="text" class="form-control" id="utgivelseskommentar" name="utgivelseskommentar" value="{{ old('utgivelseskommentar', $record->utgivelseskommentar) }}">
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
                            mfl.
                        </label>

                    </div>

                    <div class="col-sm-4">
                        <label for="kritiker_pseudonym" class="control-label">{{ trans('litteraturkritikk.kritiker_pseudonym') }}</label>
                        <input placeholder="Pseudonym" type="text" class="form-control" id="kritiker_pseudonym" name="kritiker_pseudonym" value="{{ old('kritiker_pseudonym', $record->kritiker_pseudonym) }}">
                    </div>


                    <div class="col-sm-4">

                        <label for="kritiker_kommentar">Merknad om forfatterskap:</label>
                        <input type="text" class="form-control" id="kritiker_kommentar" name="kritiker_kommentar" value="{{ old('kritiker_kommentar', $record->kritiker_kommentar) }}">

                    </div>

                </div>

            </li>
        </ul>

    </div>

    @section('script')

        <script>

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

            $('#kritikktype').selectize({
                openOnFocus: false,
                closeAfterSelect: true,
                selectOnTab: true
            });

            ['forfattere', 'kritikere'].forEach(field => {
                $(`#${field}`).selectize({
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
                });
            });

            ['spraak', 'verk_spraak', 'publikasjon', 'verk_sjanger'].forEach(field => {

                $(`#${field}`).autocomplete({}, [
                    {
                        source: (query, callback) => search(field, query, callback),
                    }
                ])

                // $(`#${field}`).selectize({
                //     valueField: 'value',
                //     labelField: 'value',
                //     searchField: 'value',
                //     options: [],
                //     delimiter: null,
                //     create: true,
                //     selectOnTab: true,
                //     openOnFocus: false,
                //     closeAfterSelect: true,
                //     load: function(query, callback) {
                //         search(field, query, callback);
                //     }
                // });
            });

        </script>

    @endsection

</div>