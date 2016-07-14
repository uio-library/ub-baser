@extends('layouts.letras')

@section('content')

        <h2>
            Post {{ $record->id }}
        </h2>
        @can('letras')
            <p>
                <a href="{{ action('LetrasController@edit', $record->id) }}">[Rediger]</a>
            </p>
        @endif
        <p>

            <table class="table">
                @foreach ($columns as $column)
                    @if (!empty($record->{$column['field']}))
                    <tr>
                        <th>
                            {{ trans('letras.' . $column['field']) }}
                        </th>
                        <td>
                            @if ($column['type'] == 'array')
                                {{ implode(', ', $record->{$column['field']}) }}
                            @else
                                {{ $record->{$column['field']} }}
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>

        </p>

@endsection