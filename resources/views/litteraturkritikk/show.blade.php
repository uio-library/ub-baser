@extends('base.show')

@section('record_toolbar')
    @parent
    <a href="mailto:norsklitteraturkritikk@ub.uio.no?subject=Feil%20i%20post&body=Hei%0A%0ADet%20ser%20ut%20som%20det%20er%20en%20feil%20i%20denne%20posten%3A%0A%0Ahttps%3A%2F%2Fub-baser.uio.no%2Fnorsk-litteraturkritikk%2F{{ $record->id }}%0A%0A%5BUtdyp%5D" class="btn btn-outline-primary">
        <em class="fa fa-envelope-o"></em>
        Meld fra om feil
    </a>
@endsection

@section('record')
    @foreach ($schema->groups as $group)
        <div class="mb-4">

            <h4>{{ $group->label }}</h4>

            <div class="py-2" style="height: 45px">
                @if ($group->label == 'Kritikken' && $record->fulltekst_url)
                    @foreach (explode(' ', $record->fulltekst_url) as $n => $url)
                        <a href="{{ $url }}" class="btn btn-outline-success btn-sm">
                            <em class="fa fa-eye"></em>
                            Vis fulltekst ({{ $n + 1 }})
                        </a>
                    @endforeach
                @elseif ($group->label == 'Omtale av' && $record->verk_fulltekst_url)
                    @foreach (explode(' ', $record->verk_fulltekst_url) as $n => $url)
                        <a href="{{ $url }}" class="btn btn-outline-success btn-sm">
                            <em class="fa fa-eye"></em>
                            Vis fulltekst ({{ $n + 1 }})
                        </a>
                    @endforeach
                @elseif ($group->label != 'Databaseposten')
                    <national-library-search
                        base-url="{{ $base->action('nationalLibrarySearch') }}"
                        :record-id="{{ $record->id }}"
                        :query="{{ json_encode($record->nationalLibrarySearchQuery($group->label)) }}"
                    ></national-library-search>
                @endif

                @if ($group->label == 'Omtale av')
                    <a href="https://bibsys-almaprimo.hosted.exlibrisgroup.com/primo-explore/search?{{ $record->oriaSearchLink() }}" class="btn btn-outline-success btn-sm">
                        <em class="fa fa-search"></em>
                        Søk i Oria
                    </a>
                @endif
            </div>

            <dl class="row">
                @foreach ($group->fields as $field)
                    @if ($field->showInRecordView && !$record->isEmpty($field->getModelAttribute()))
                        <dt class="col-sm-3 text-sm-right">
                            {{ trans('litteraturkritikk.' . $field->key) }}:
                        </dt>
                        <dd class="col-sm-9 {{ $field->key }}">

                            @if ($field->type == 'entities')
                                @foreach ($record->{$field->modelAttribute} as $person)
                                    <a href="{{ $base->action('PersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                                        $person->pivot->pseudonym ? ', under pseudonymet «' . $person->pivot->pseudonym . '»' : ''
                                    }}{{
                                        !in_array($person->pivot->person_role, [['kritiker'], ['forfatter']]) ? ' (' . implode(', ', $person->pivot->person_role) . ')' : ''
                                    }}{{
                                        $person->pivot->kommentar ? ' (' . $person->pivot->kommentar . ')' : ''
                                    }}<br>
                                @endforeach
                                @if ($record->{$field->key . '_mfl'})
                                    <em>mfl.</em>
                                @endif

                            @elseif (method_exists($field, 'formatValue') && mb_strpos($field->key, 'spraak') === false)

                                {!! $field->formatValue($record->{$field->key}, $base) !!}

                            @elseif (is_array($record->{$field->key}))

                                {{ implode(';', $record->{$field->key}) }}

                            @elseif ($field->key == 'created_at')

                                {{ $record->{$field->key} }}
                                av {{ $record->createdBy ? $record->createdBy->name : ' (import)' }}

                            @elseif ($field->key == 'updated_at')

                                {{ $record->{$field->key} }}
                                av {{ $record->updatedBy ? $record->updatedBy->name : ' (import)' }}

                            @elseif ($field->key == 'deleted_at')

                                {{ $record->{$field->key} ?: 'Nei' }}

                            @else

                                {{ $record->{$field->key} }}

                            @endif
                        </dd>

                    @endif
                @endforeach
            </dl>
        </div>
    @endforeach
@endsection
