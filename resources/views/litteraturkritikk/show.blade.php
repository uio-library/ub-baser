@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            <a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-left"></i> Tilbake</a>
            @can('litteraturkritikk')
            &nbsp;
            <a href="{{ action('LitteraturkritikkController@edit', $record->id) }}"><i class="fa fa-edit"></i> Rediger post</a>
            @endif
        </p>

        <h2>
            Post {{ $record->id }}
        </h2>

        @foreach ($record::getColumns() as $group)
            @if (!isset($group['display']) || $group['display'] !== false)

                <h3>{{ $group['label'] }}</h3>
                <dl class="dl-horizontal">
                    @foreach ($group['fields'] as $field)
                        @if (!isset($field['display']) || $field['display'] !== false)
                            <dt>
                                {{ trans('litteraturkritikk.' . $field['key']) }}:
                            </dt>
                            <dd>

                                @if ($field['key'] == 'kritiker')

                                    @foreach ($record->kritikere as $person)
                                        <a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>
                                    @endforeach
                                    @if ($record->kritiker_mfl)
                                        <em>mfl.</em>
                                    @endif

                                @elseif ($field['key'] == 'verk_forfatter')

                                    @foreach ($record->forfattere as $person)
                                        <a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                                            ($person->pivot->person_role != 'forfatter') ? ' (' . $person->pivot->person_role . ')' : ''
                                        }}<br>
                                    @endforeach
                                    @if ($record->forfatter_mfl)
                                        <em>mfl.</em>
                                    @endif

                                @elseif (is_array($record->{$field['key']}))
                                    {{ implode(', ', $record->{$field['key']}) }}
                                @else
                                    {{ $record->{$field['key']} }}
                                @endif
                            </dd>
                        @endif
                    @endforeach
                </dl>
            @endif
        @endforeach

        @if (Auth::check())
            <h3>Metadata</h3>
            <dl class="dl-horizontal">
                <dt>Opprettet:</dt>
                <dd>{{ $record->created_at }} av {{ $record->createdBy ? $record->createdBy->name : ' (import)' }}</dd>
                <dt>Sist endret:</dt>
                <dd>{{ $record->updated_at }} av {{ $record->updatedBy ? $record->updatedBy->name : ' (import)' }}</dd>
            </dl>
        @endif

@endsection
