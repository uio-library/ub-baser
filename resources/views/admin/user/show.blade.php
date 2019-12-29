@extends('layout')

@section('content')

@include('shared.errors')

<div class="card my-3">

    <div class="card-header">
        {{ $user->name }}
    </div>


    <table class="table">
        <caption class="sr-only">Liste over brukere</caption>
        <tbody>
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
