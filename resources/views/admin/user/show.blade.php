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
        <tbody>
            <tr>
                <td>Navn</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>E-post</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Registrert</td>
                <td>{{ $user->created_at }}</td>
            </tr>
            <tr>
                <td>Sist innlogget</td>
                <td>{{ $user->last_login_at }}</td>
            </tr>
            <tr>
                <td>Tilganger</td>
                <td>{{ implode(', ', $user->rights) }}</td>
            </tr>
        </tbody>
    </table>

</div>

@endsection
