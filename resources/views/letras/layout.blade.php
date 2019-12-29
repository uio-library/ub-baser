@extends('layout')
@section('db-title', 'Letras')

@section('header')
<a href="{{ action('\App\Bases\Letras\Controller@index') }}">Letras</a>
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
            <a href="https://www.ub.uio.no/om/ansatte/uhs/uhsfagstudier/jmaria/index.html">Jose Maria Izquierdo</a>.
        </li>
    </ul>
@endsection
