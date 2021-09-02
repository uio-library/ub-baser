@extends('opes.layout')

@section('content')

    <h2>
        Edition #{{ $record->id }}
    </h2>

    @if ($record->record)
        <p>
            Belongs to record:
            <a href="{{ $base->action('show', $record->record->id) }}">{{ $record->recordView }}</a>
        </p>
    @endif

    <table>
        @foreach ($schema->fields as $field)
            <tr>
                <th class="text-right">
                    {{ __('opes.edition.' . $field->key) }}
                </th>
                <td>
                    {{ $record->{$field->key} }}
                </td>
            </tr>
        @endforeach
    </table>

    @can($base->id)
        <a href="{{ $base->action('EditionController@edit', $record->id) }}" class="btn btn-outline-primary">
            <em class="fa fa-edit"></em>
            Edit edition
        </a>
        <!--
        <a href="{{ $base->action('EditionController@delete', $record->id) }}" class="btn btn-outline-danger">
            <em class="fa fa-trash"></em>
            Delete edition
        </a>
        -->
    @endcan


@endsection

