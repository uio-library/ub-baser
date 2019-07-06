@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            @if ($view == 'table')
                <a href="{{ action('LitteraturkritikkController@index') . $viewQs }}view=list"><i class="fa fa-list"></i> Listevisning</a>&nbsp;
            @else
                <a href="{{ action('LitteraturkritikkController@index') . $viewQs }}view=table"><i class="fa fa-table"></i> Tabellvisning</a>
            @endif

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

                @include('litteraturkritikk.search')

            </div>
        </div>

        <p>
            {{ $records->total() }} poster
        </p>

        @if ($view == 'table')

            Vis kolonner:

            <select class="selectpicker" multiple>
                @foreach ($allFields as $field)
                    <option value="{{ $field['key'] }}">{{ $field['label'] }}</option>
                @endforeach
            </select>

            <table id="table1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    @foreach ($allFields as $field)
                        @if (!isset($field['display']) || $field['display'])
                            <th>{{ $field['label'] }}</th>
                        @endif
                    @endforeach
                </tr>
                </thead>
            </table>

        @else

            <table class="table">
            @foreach ($records as $record)
                <tr>
                    <td>
                        {!! $record->representation() !!}
                    </td>
                </tr>
            @endforeach
            </table>

            {!! $records->render() !!}

        @endif

@endsection

@section('script')

    <script>
        $(document).ready(function() {

            let visibleColumns = [
                'aar_numeric',
                'dato',
                'verk_tittel',
            ];
            if (sessionStorage.getItem('ub-baser-litteraturkritikk-selected-columns') !== null) {
                visibleColumns = JSON.parse(sessionStorage.getItem('ub-baser-litteraturkritikk-selected-columns'));
            }

            let columns = [
                @foreach ($allFields as $field)
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
            ];

            let columnKeys = columns.map(x => x.data);

            let defaultOrder = [[0, 'asc']];
            if (visibleColumns.indexOf('aar_numeric') !== -1) {
                defaultOrder = [[columnKeys.indexOf('aar_numeric'), 'desc']];
            }
            console.log('Order:', defaultOrder);

            let table = $('#table1').DataTable( {
                language: {
                    "sEmptyTable": "Ingen data tilgjengelig i tabellen",
                    "sInfo": "Viser _START_ til _END_ av _TOTAL_ linjer",
                    "sInfoEmpty": "Viser 0 til 0 av 0 linjer",
                    "sInfoFiltered": "(filtrert fra _MAX_ totalt antall linjer)",
                    "sInfoPostFix": "",
                    "sInfoThousands": " ",
                    "sLoadingRecords": "Laster...",
                    "sLengthMenu": "Vis _MENU_ linjer",
                    "sLoadingRecords": "Laster...",
                    "sProcessing": "Laster...",
                    "sSearch": "S&oslash;k:",
                    "sUrl": "",
                    "sZeroRecords": "Ingen linjer matcher s&oslash;ket",
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
                ajax: {
                    url: '{!! action('LitteraturkritikkController@index') . $viewQs !!}',
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

            console.log(visibleColumns);

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


        } );
    </script>

@endsection
