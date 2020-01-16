@extends('layout')

@section('db-title', 'UB-baser')

@section('content')

        <ul id="database_list">
            @foreach($bases as $item)
                <li>
                    <a href="{{ $item->action('index') }}">{{ $item->title }}</a>
                    {{--
                    {!! $base->getIntro() !!}
                    --}}
                </li>
            @endforeach
        </ul>

@endsection
