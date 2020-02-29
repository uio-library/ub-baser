@extends('layout')
@section('db-title', 'Dommers populærnavn')

@section('h1-title')
    <a href="{{ action('\App\Bases\Dommer\Controller@index') }}">Dommers populærnavn</a>
@endsection

