@extends('opes.layout')

@section('content')

    @can($base->id)
        <a href="{{ $base->action('EditionController@edit', $record->id) }}" class="btn btn-outline-primary">
            <em class="fa fa-edit"></em>
            Edit edition
        </a>
        <a href="{{ $base->action('EditionController@delete', $record->id) }}" class="btn btn-outline-danger">
            <em class="fa fa-trash"></em>
            Delete edition
        </a>
    @endcan

    TODO
@endsection

