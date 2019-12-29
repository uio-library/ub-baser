@extends('litteraturkritikk.layout')

@section('content')

    <div class="pb-3">

        <a href="{{ URL::previous() }}" class="btn btn-outline-primary">
            <em class="fa fa-arrow-circle-left"></em>
            Tilbake
        </a>

        <a href="mailto:norsklitteraturkritikk@ub.uio.no?subject=Feil%20i%20post&body=Hei%0A%0ADet%20ser%20ut%20som%20det%20er%20en%20feil%20i%20denne%20posten%3A%0A%0Ahttps%3A%2F%2Fub-baser.uio.no%2Fnorsk-litteraturkritikk%2F{{ $record->id }}%0A%0A%5BUtdyp%5D" class="btn btn-outline-primary">
            <em class="fa fa-envelope-o"></em>
            Meld fra om feil
        </a>

        @can('litteraturkritikk')

            <a href="{{ $base->action('edit', $record->id) }}" class="btn btn-outline-primary">
                <em class="fa fa-edit"></em>
                Rediger post
            </a>

            @if ($record->trashed())
                <form style="display: inline-block" action="{{ $base->action('restore', $record->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-xs">
                        <em class="fa fa-undo"></em>
                        Gjenopprett
                    </button>
                </form>
            @else
                <form style="display: inline-block" action="{{ $base->action('destroy', $record->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-outline-danger btn-xs">
                        <em class="fa fa-trash"></em>
                        Slett
                    </button>
                </form>
            @endif
        @endcan
    </div>


    <div class="d-flex flex-column flex-sm-row">

        <div class="flex-grow-1">
            @if ($record->trashed())
                <h2>
                    <s>Post {{ $record->id }}</s>
                </h2>
                <div class="alert alert-danger" role="alert">
                    Denne posten er mykslettet og vises ikke i søk eller for ikke-innloggede brukere.
                    Du kan imidlertid enkelt gjenopprette den om du ønsker det.
                </div>
            @else
                <h2>
                    Post {{ $record->id }}
                </h2>
            @endif

            @foreach ($schema->groups as $group)
                <h4 class="mt-4">{{ $group->label }}</h4>

                <div class="py-2">
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
                        <a href="https://www.nb.no/search?{{ $record->nationalLibrarySearchLink($group->label) }}" class="btn btn-outline-success btn-sm">
                            <em class="fa fa-search"></em>
                            Søk etter fulltekst i NB
                        </a>
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
                        @if ($field->displayable && !empty($record->{$field->getModelAttribute()}))
                            <dt class="col-sm-3 text-sm-right">
                                {{ trans('litteraturkritikk.' . $field->key) }}:
                            </dt>
                            <dd class="col-sm-9">

                                @if ($field->type == 'persons')

                                    @foreach ($record->{$field->modelAttribute} as $person)
                                        <a href="{{ $base->action('PersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
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

                                @elseif ($field->type == 'url')
                                    @foreach (explode(' ', $record->{$field->key}) as $url)
                                        <a href="{{ $url }}">{{ $url }}</a><br>
                                    @endforeach

                                @elseif ($field->type == 'enum')

                                    {{ $field->formatValue($record->{$field->key}) }}

                                @elseif (is_array($record->{$field->key}))

                                    @foreach ($record->{$field->key} as $value)
                                        <a class="badge badge-primary" href="{{ $base->action('index', [
                                            'f0' => $field->getColumn(),
                                            'v0' => $value,
                                        ]) }}">{{ $value }}</a>
                                    @endforeach

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

            @endforeach

        </div>

    </div>

@endsection
