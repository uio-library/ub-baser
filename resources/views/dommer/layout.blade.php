@extends('layout')
@section('db-title', 'Dommers populærnavn')

@section('header')
<a href="{{ action('\App\Bases\Dommer\Controller@index') }}">Dommers populærnavn</a>
@endsection

