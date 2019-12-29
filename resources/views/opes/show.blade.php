@extends('opes.layout')

@section('content')

        <h2>
            Post {{ $record->id }}
        </h2>
        @can('opes')
            <p>
                <a href="{{ action('Controller@edit', $record->id) }}">[Rediger]</a>
            </p>
        @endif
        <p>

            <table class="table">
                @foreach ($columns as $column)
                    @if (!empty($record->{$column['field']}))
                    <tr>
                        <th>
                            {{ trans('opes.' . $column['field']) }}
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

        <h2>
            Post {{$record->GetPubl}}
        </h2>

@endsection
