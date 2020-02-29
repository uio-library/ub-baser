@extends('layout')
@section('db-title', 'Bibsys-arkiv UBO')

@section('header')
    @parent

    <div class="container pb-3">
        Dette arkivet inneholder katalogposter fra Bibsys-katalogen for alle poster
        der UBO hadde beholdning da Bibsys-systemet («Blåskjermen») ble tatt ut av bruk
        til fordel for Alma i 2015.

        Arkivet inneholder bibliografiske poster (objektposter) i
        <a href="/static/bibsys-marc-dok.pdf">Bibsys-MARC</a>-formatet,
        samt beholdningsinformasjon fra dokumentpostene.
        Interne lukkede bemerkninger er tatt ut av personvernhensyn.
    </div>
@endsection

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            ...
        </li>
        <li>
            ...
        </li>
    </ul>
@endsection
