<div class="ltk_work">
    @foreach ($schema->fields as $field)
        <div class="ltk_row">
            @if ($field->showInRecordView && !$record->isEmpty($field->getModelAttribute()))
                <div class="ltk_field_name" style="color: #555;">
                    {{ trans('litteraturkritikk.verk.' . $field->key) }}:
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
            @endif
        </div>
    @endforeach

    <div class="ltk_fulltext_links">
        @if ($record->verk_fulltekst_url)
            @foreach (explode(' ', $record->verk_fulltekst_url) as $n => $url)
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

        <a href="https://bibsys-almaprimo.hosted.exlibrisgroup.com/primo-explore/search?{{ $record->oriaSearchLink() }}" class="btn btn-outline-success btn-sm">
            <em class="fa fa-search"></em>
            Søk i Oria
        </a>
    </div>

</div>
