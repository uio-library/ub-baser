@extends('layouts.beyer')

@section('content')

        <h2>
            Norsk litteraturkritikk
        </h2>

        @can('beyer')
            <p>
                <a href="{{ action('BeyerController@create') }}">+ Opprett ny post</a>
            </p>
        @endif

        <div class="panel panel-default">
            <div class="panel-body">

                @include('beyer.search')

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
