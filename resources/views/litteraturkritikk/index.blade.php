@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            @can('litteraturkritikk')
                <a href="{{ action('LitteraturkritikkController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
                &nbsp;
                <a href="{{ route('litteraturkritikk.intro.edit') }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
            @endcan
        </p>

        <div class="lead">
            {!! $intro !!}
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <litteraturkritikk-search-form
                    :initial-query="{{ json_encode($query) }}"
                    :search-fields="{{ json_encode($searchFields) }}"
                    :advanced-search="{{ json_encode($advancedSearch) }}"
                ></litteraturkritikk-search-form>
            </div>
        </div>

        Vis kolonner:

        <select class="selectpicker" multiple>
            @foreach ($allFields as $group)
                @if (!isset($group['display']) || $group['display'])
                    <optgroup label="{{ $group['label'] }}">
                    @foreach ($group['fields'] as $field)
                        @if (!isset($field['display']) || $field['display'])
                            <option value="{{ $field['key'] }}">{{ $field['label'] }}</option>
                        @endif
                    @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>

        <table id="table1" class="table table-striped hover" style="width:100%">
            <thead>
            <tr>
                @foreach ($allFields as $group)
                    @foreach ($group['fields'] as $field)
                        @if (!isset($field['display']) || $field['display'])
                            <th>{{ $field['label'] }}</th>
                        @endif
                    @endforeach
                @endforeach
            </tr>
            </thead>
        </table>

@endsection

@section('script')

    <script>
        $(document).ready(function() {

            let visibleColumns = [

                // Verket
                'verk_tittel',
                'verk_forfatter',
                'verk_aar',

                // Kritikken
                'kritiker',
                'publikasjon',
                'aar',
                'dato',
            ];
            if (sessionStorage.getItem('ub-baser-litteraturkritikk-selected-columns') !== null) {
                visibleColumns = JSON.parse(sessionStorage.getItem('ub-baser-litteraturkritikk-selected-columns'));
            }

            let columns = [
                @foreach ($allFields as $group)
                    @foreach ($group['fields'] as $field)
                        @if (!isset($field['display']) || $field['display'])

                        {
                            data: "{{ $field['key'] }}",
                            visible: visibleColumns.indexOf('{{ $field['key'] }}') !== -1,
                            render: function ( data, type, row ) {
                                if (data === null) {
                                    data = '–';
                                }
                                return `<a href="{{ action('LitteraturkritikkController@index') }}/${row.id}">${data}</a>`;
                            },
                        },
                        @endif
                    @endforeach
                @endforeach
            ];

            let columnKeys = columns.map(x => x.data);

            let defaultOrder = [[0, 'asc']];
            if (visibleColumns.indexOf('verk_aar') !== -1) {
                defaultOrder = [[columnKeys.indexOf('verk_aar'), 'desc']];
            }

            let table = $('#table1').DataTable( {
                language: {
                    "sEmptyTable": "Ingen data tilgjengelig i tabellen",
                    "sInfo": "Viser _START_ til _END_ av _TOTAL_ poster",
                    "sInfoEmpty": "Viser 0 til 0 av 0 poster",
                    "sInfoFiltered": "(filtrert fra _MAX_ totalt antall poster)",
                    "sInfoPostFix": "",
                    "sInfoThousands": " ",
                    "sLoadingRecords": "Laster...",
                    "sLengthMenu": "Vis _MENU_ poster",
                    "sLoadingRecords": "Laster...",
                    "sProcessing": "Laster...",
                    "sSearch": "S&oslash;k:",
                    "sUrl": "",
                    "sZeroRecords": "Ingen poster matcher s&oslash;ket",
                    "oPaginate": {
                        "sFirst": "F&oslash;rste",
                        "sPrevious": "Forrige",
                        "sNext": "Neste",
                        "sLast": "Siste"
                    },
                    "oAria": {
                        "sSortAscending": ": aktiver for å sortere kolonnen stigende",
                        "sSortDescending": ": aktiver for å sortere kolonnen synkende"
                    }
                },
                pageLength: 50,
                lengthMenu: [ 10, 50, 100, 500, 1000 ],
                ajax: {
                    url: '{!! action('LitteraturkritikkController@index') . $qs !!}',
                    data: function ( d ) {
                        return  $.extend( {}, d, {!! $qsJson !!} );
                    }
                },
                columns: columns,
                order: defaultOrder,

                searching: false,
                processing: true,
                serverSide: true,
            } );

            $('.selectpicker').val(visibleColumns);

            $('.selectpicker').on('change', function(evt) {
                let visibleColumns = $('.selectpicker').val();  // array of keys

                sessionStorage.setItem('ub-baser-litteraturkritikk-selected-columns', JSON.stringify(visibleColumns));

                columns.forEach(function(col, idx) {
                    let visible = visibleColumns.indexOf(col.data) !== -1;
                    table.column(idx).visible(visible);
                });

                // var column = table.column( $(this).attr('data-column') );

            })

            $('#table1').on('click', 'tbody > tr > td', function ($event) {
                // 'this' refers to the current <td>
                let link = $(this).find('a').attr('href');
                if ($event.which !== 1) {
                    // Pass
                } else if ($event.ctrlKey || $event.metaKey) {
                    window.open(link, '_blank');
                    return false;
                } else {
                    window.location = link;
                    return false;
                }
            });
        } );
    </script>

@endsection
