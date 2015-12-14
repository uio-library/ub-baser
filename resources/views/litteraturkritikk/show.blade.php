@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            <a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-left"></i> Tilbake</a>
            @can('litteraturkritikk')
            &nbsp;
            <a href="{{ action('BeyerController@edit', $record->id) }}"><i class="fa fa-edit"></i> Rediger post</a>
            @endif
        </p>

        <h2>
            Post {{ $record->id }}
        </h2>
        <p>

            <table class="table">
                @foreach ($columns as $column)
                    @if (!empty($record->{$column['field']}))
                    <tr>
                        <th>
                            {{ trans('litteraturkritikk.' . $column['field']) }}
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
