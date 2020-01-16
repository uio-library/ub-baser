@extends('layout')
@section('db-title', 'OPES')

@section('header')
<a href="{{ action('\App\Bases\Opes\Controller@index') }}">OPES</a>
@endsection

@section('head')

@endsection

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            Basen driftes av <a href="https://www.ub.uio.no/">Universitetsbiblioteket</a>.
        </li>
        <li>
            For spørsmål og innspill, kontakt
            <a href="https://www.ub.uio.no/om/ansatte/uhs/uhsfagstudier/federia/">Federico Aurora</a>.
        </li>
    </ul>
@endsection
