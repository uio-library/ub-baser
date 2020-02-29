@extends('layout')

@section('db-title', 'OPES')

@section('h1-title')
    <a href="{{ $base->action('index') }}">OPES – Oslo Papyri Electronic System</a>
@endsection

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            Contact for this database:<br>
            <a href="https://www.ub.uio.no/om/ansatte/uhs/uhsfagstudier/federia/">Federico Aurora</a>
        </li>
    </ul>
@endsection
