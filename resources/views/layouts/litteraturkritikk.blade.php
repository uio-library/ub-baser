@extends('layouts.master')
@section('db-title', 'Norsk litteraturkritikk')

@section('header-part')
: <a href="{{ action('LitteraturkritikkController@index') }}">Norsk litteraturkritikk</a>
@endsection

@section('head')

@endsection

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            Basen driftes av <a href="https://www.ub.uio.no/">Universitetsbiblioteket</a>
            i samarbeid med ...
            <br>
        </li>
    </ul>
@endsection