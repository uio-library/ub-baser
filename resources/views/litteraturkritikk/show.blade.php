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

                                @if ($field['datatype'] == 'person')

                                    @foreach ($record->{$field['model_attribute']} as $person)
                                        <a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                                            $person->pivot->pseudonym ? ', under pseudonymet «' . $person->pivot->pseudonym . '»' : ''
                                        }}{{
                                            !in_array($person->pivot->person_role, ['kritiker', 'forfatter']) ? ' (' . $person->pivot->person_role . ')' : ''
                                        }}{{
                                            $person->pivot->kommentar ? ' (' . $person->pivot->kommentar . ')' : ''
                                        }}
                                    @endforeach
                                    @if ($record->{$field['key'] . '_mfl'})
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
