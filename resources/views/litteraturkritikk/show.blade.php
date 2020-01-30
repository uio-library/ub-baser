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

            <div class="ltk_record">
                @foreach ($group->fields as $field)
                    @if ($field->showInRecordView && !$record->isEmpty($field->getModelAttribute()))
                        <div class="ltk_row">
                            <div class="ltk_field_name">
                                {{ trans('litteraturkritikk.' . $field->key) }}:
                            </div>
                            <div class="ltk_field_value {{ $field->key }}">

                                @if ($field->type == 'entities')
                                    @foreach ($record->{$field->modelAttribute} as $entity)
                                        @component('litteraturkritikk.components.' . $field->entityType['name'], [
                                            'record' => $entity,
                                            'schema' => $field->entityType['schema'],
                                        ])
                                        @endcomponent
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
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($group->label == 'Kritikken')
                    <div class="ltk_fulltext_links">
                        @if ($record->fulltekst_url)
                            @foreach (explode(' ', $record->fulltekst_url) as $n => $url)
                                <a href="{{ $url }}" class="btn btn-outline-success btn-sm">
                                    <em class="fa fa-file-text"></em>
                                    Vis fulltekst ({{ $n + 1 }})
                                </a>
                            @endforeach
                        @else
                            <national-library-search
                                base-url="{{ $base->action('nationalLibrarySearch') }}"
                                :record-id="{{ $record->id }}"
                                :query="{{ json_encode($record->nationalLibrarySearchQuery($group->label)) }}"
                            ></national-library-search>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    @endforeach
@endsection
