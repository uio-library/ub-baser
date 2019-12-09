@extends('layouts.master')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action('Admin\UserController@index') }}">{{ trans('messages.manageusers') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
</nav>

@include('shared.errors')

<div class="card my-3">

    <div class="card-header">
        Bruker #{{$user->id}}
    </div>


    <table class="table">
        <caption class="sr-only">Liste over brukere</caption>
        <tbody>
            <tr>
                <th scope="row">Navn</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th scope="row">E-post</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th scope="row">Registrert</th>
                <td>{{ $user->created_at }}</td>
            </tr>
            <tr>
                <th scope="row">Sist innlogget</th>
                <td>{{ $user->last_login_at }}</td>
            </tr>
            <tr>
                <th scope="row">Tilganger</th>
                <td>{{ implode(', ', $user->rights) }}</td>
            </tr>
        </tbody>
    </table>

</div>

@endsection
