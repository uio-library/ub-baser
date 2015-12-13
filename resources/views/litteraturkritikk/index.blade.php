@extends('layouts.litteraturkritikk')

@section('content')

        @can('litteraturkritikk')
            <p>
                <a href="{{ action('BeyerController@create') }}"><i class="fa fa-plus-circle"></i> Opprett ny post</a>
                &nbsp;
                <a href="{{ route('litteraturkritikk.intro.edit') }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
            </p>
        @endif

        <p>
            {!! $intro !!}
        </p>

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
                    <a href="{{ action('BeyerController@show', $record->id) }}">
                        {!! $record->representation() !!}
                    </a>
                </td>

            </tr>
        @endforeach
        </table>

        {!! $records->render() !!}

@endsection
