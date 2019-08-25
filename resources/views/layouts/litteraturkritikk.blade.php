@extends('layouts.master')
@section('db-title', 'Norsk litteraturkritikk')

@section('header')
<a href="{{ action('LitteraturkritikkController@index') }}">Norsk litteraturkritikk</a>
@endsection

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            Nettstedet drives av
            <a href="https://www.ub.uio.no/">Universitetsbiblioteket</a>
            sammen med
            <a href="https://www.hf.uio.no/iln/">Institutt for lingvistiske og nordiske studier</a>.
            <br>
            Kontakt oss: <a href="mailto:norsklitteraturkritikk@ub.uio.no">norsklitteraturkritikk@ub.uio.no</a>
        </li>
    </ul>
@endsection
