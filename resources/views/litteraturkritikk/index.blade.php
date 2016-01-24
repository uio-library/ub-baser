@extends('layouts.litteraturkritikk')

@section('content')

        @can('litteraturkritikk')
            <p>
                <a href="{{ action('LitteraturkritikkController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
                &nbsp;
                <a href="{{ route('litteraturkritikk.intro.edit') }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
            </p>
        @endif

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

@endsection
