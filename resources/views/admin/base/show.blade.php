@extends('layout')

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action('Admin\BaseController@index') }}">{{ trans('messages.managebases') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $base->title }}</li>
        </ol>
    </nav>

    @include('shared.errors')

    <div class="my-3">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Innstilling</th>
                    <th scope="col">Verdi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        id
                    </td>
                    <td class="text-monospace">
                        {{ $base->id }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Base path:
                    </td>
                    <td class="text-monospace">
                        {{ url($base->basepath) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        languages
                    </td>
                    <td class="text-monospace">
                        {{ implode(', ', $base->languages) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        default_language
                    </td>
                    <td class="text-monospace">
                        {{ $base->default_language }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Namespace
                    </td>
                    <td class="text-monospace">
                        {{ $base->fqn() }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Schema
                    </td>
                    <td class="text-monospace">
                        {{ $base->getClass('Schema') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Record
                    </td>
                    <td class="text-monospace">
                        {{ $base->getClass('Record') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Record view
                    </td>
                    <td class="text-monospace">
                        {{ $base->getClass('RecordView') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Autocomplete service
                    </td>
                    <td class="text-monospace">
                        {{ $base->getClass('AutocompleteService') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection
