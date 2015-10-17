@extends('layouts.master')

@section('db-title', 'UB-baser')

@section('content')

        <ul>
            <li>
                <a href="{{ action('BeyerController@index') }}"><strong>Norsk litteraturkritikk / Beyer</strong></a>
            </li>
            <li>
                <a href="{{ action('OpesController@index') }}">OPES</a>
            </li>
            <li>
                <a href="{{ action('LetrasController@index') }}">Letras</a>
            </li>
            <li>
                <a href="{{ action('DommerController@index') }}">Dommers populærnavn</a>
            </li>
        </ul>

@endsection
