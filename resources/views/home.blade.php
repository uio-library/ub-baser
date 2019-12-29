@extends('layout')

@section('db-title', 'UB-baser')

@section('content')

        <ul id="database_list">
            @foreach($bases as $base)
                <li>
                    <a href="{{ $base->action('index') }}">{{ $base->title }}</a>
                    {{--
                    {!! $base->getIntro() !!}
                    --}}
                </li>
            @endforeach
        </ul>

@endsection
