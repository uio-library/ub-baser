@extends('layouts.master')
@section('db-title', 'Dommers populærnavn')

@section('header')
<a href="{{ action('DommerController@index') }}">Dommers populærnavn</a>
@endsection

