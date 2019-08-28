@extends('layouts.litteraturkritikk')

@section('content')

    <p>
        <a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-left"></i> Tilbake</a>
        @can('litteraturkritikk')
        &nbsp;
        <a href="{{ action('LitteraturkritikkController@edit', $record->id) }}"><i class="fa fa-edit"></i> Rediger post</a>
        @endif
    </p>


    <div class="d-flex flex-column flex-sm-row">

        <div class="flex-grow-1">
            <h2>
                Post {{ $record->id }}
            </h2>

            @foreach ($schema->groups as $group)
                <h4 class="mt-4">{{ $group->label }}</h4>
                <dl class="row">
                    @foreach ($group->fields as $field)
                        @if ($field->displayable)
                            <dt class="col-sm-3 text-sm-right">
                                {{ trans('litteraturkritikk.' . $field->key) }}:
                            </dt>
                            <dd class="col-sm-9">

                                @if ($field->type == 'persons')

                                    @foreach ($record->{$field->modelAttribute} as $person)
                                        <a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                                            $person->pivot->pseudonym ? ', under pseudonymet «' . $person->pivot->pseudonym . '»' : ''
                                        }}{{
                                            !in_array($person->pivot->person_role, ['kritiker', 'forfatter']) ? ' (' . $person->pivot->person_role . ')' : ''
                                        }}{{
                                            $person->pivot->kommentar ? ' (' . $person->pivot->kommentar . ')' : ''
                                        }}<br>
                                    @endforeach
                                    @if ($record->{$field->key . '_mfl'})
                                        <em>mfl.</em>
                                    @endif

                                @elseif (is_array($record->{$field->key}))

                                    {{ implode(', ', $record->{$field->key}) }}

                                @else

                                    {{ $record->{$field->key} }}

                                @endif
                            </dd>

                        @endif
                    @endforeach
                </dl>
            @endforeach

            @if (Auth::check())
                <h3>Metadata</h3>
                <dl class="row">
                    <dt class="col-sm-3 text-sm-right">Opprettet:</dt>
                    <dd class="col-sm-9">{{ $record->created_at }} av {{ $record->createdBy ? $record->createdBy->name : ' (import)' }}</dd>
                    <dt class="col-sm-3 text-sm-right">Sist endret:</dt>
                    <dd class="col-sm-9">{{ $record->updated_at }} av {{ $record->updatedBy ? $record->updatedBy->name : ' (import)' }}</dd>
                </dl>
            @endif
        </div>

        <div class="flex-grow-0 flex-shrink-0" style="width: 200px;">
            <div style="text-align: center; border: 1px solid #eee; border-radius: 3px;" class="px-4 py-3">
                Er det feil i posten?<br>
                <a href="mailto:norsklitteraturkritikk@ub.uio.no?subject=Feil%20i%20post&body=Hei%0A%0ADet%20ser%20ut%20som%20det%20er%20en%20feil%20i%20denne%20posten%3A%0A%0Ahttps%3A%2F%2Fub-baser.uio.no%2Fnorsk_litteraturkritikk%2F{{ $record->id }}%0A%0A%5BUtdyp%5D" class="btn btn-warning">
                    <em class="fa fa-envelope-o"></em>
                    Meld fra
                </a>
            </div>
        </div>
    </div>

@endsection
