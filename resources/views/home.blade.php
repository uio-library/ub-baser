@extends('layouts.master')

@section('db-title', 'UB-baser')

@section('content')

        <ul id="database_list">
            <li>
                <a href="{{ action('LitteraturkritikkController@index') }}">Norsk litteraturkritikk</a>
            </li>
            <li>
                <a href="{{ action('LetrasController@index') }}">Letras</a>
            </li>
            <li>
                <a href="{{ action('DommerController@index') }}">Dommers populærnavn</a>
            </li>
            <li>
                <a href="{{ action('BibsysController@index') }}">Bibsys-arkiv UBO</a>
            </li>
        </ul>

@endsection
