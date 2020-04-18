@extends('base.show')

@section('record')

    @if (count($record->publications) && $record->publications[0]->papyri_info_link)
        <a class="btn btn-link" href="{{ $record->publications[0]->papyri_info_link}}">
            <em class="fa fa-arrow-right"></em>
            View record at papyri.info
        </a>
    @endif

    <div class="row record-view">
        <div class="col-sm-6">
            @if ($record->fullsizefront_r1 || $record->fullsizeback_r1)
                @if ($record->fullsizefront_r1)
                <div>
                    <image-viewer
                        id="ods_front"
                        tile-src="https://ub-media.uio.no/OPES/pyramids/{{ $record->fullsizefront_r1 }}.dzi"
                    ></image-viewer>
                    <a target="_blank" href="https://ub-media.uio.no/OPES/jpg/{{ $record->fullsizefront_r1 }}">Open image in new window</a>
                </div>
                @endif

                @if ($record->fullsizeback_r1)
                    <div>
                        <image-viewer
                            id="ods_back"
                            tile-src="https://ub-media.uio.no/OPES/pyramids/{{ $record->fullsizeback_r1 }}.dzi"
                        ></image-viewer>
                        <a target="_blank" href="https://ub-media.uio.no/OPES/jpg/{{ $record->fullsizeback_r1 }}">Open image in new window</a>
                    </div>
                @endif
            @else
                <div class="alert alert-warning">
                    <em class="fa fa-exclamation-triangle"></em>
                    No scans published for this document yet.
                </div>
            @endif
        </div>
        <div class="col-sm">

            {{--
                <table>

                @foreach(['inv_no', 'title_or_type', 'publ_side'] as $key)
                <tr>
                    <th>
                        {{ __('opes.' . $key) }}:
                    </th>
                    <td>
                        {{ $record->{$key} }}
                    </td>
                </tr>
                @endforeach
            </table>
            --}}


            @foreach ($schema->groups as $group)
                @if (!in_array($group->label, ['Images', 'Information on publication']))
                    <h4>{{ $group->label }}</h4>
                    <dl class="row">
                        @foreach ($group->fields as $field)
                            @if ($field->showInRecordView)
                                <dt class="col-sm-3 text-sm-right">
                                    {{ $field->label }}:
                                </dt>
                                <dd class="col-sm-9">
                                    @if (empty($record->{$field->key}))
                                        <span class="text-muted">–</span>
                                    @elseif (is_array($record->{$field->key}))
                                        @foreach($record->{$field->key} as $value)
                                            <a href="{{ $base->action('index', ['f0' => $field->key, 'v0' => $value]) }}" class="badge badge-primary">{{ $value }}</a>
                                        @endforeach
                                    @else
                                        {{ $record->{$field->key} }}
                                    @endif
                                </dd>
                            @endif
                        @endforeach
                    </dl>
                @endif
            @endforeach

            <h4>Publications</h4>
            <ul class="list-group">
                @foreach ($record->publications as $publication)
                    <li class="list-group-item">
                        <dl class="row">
                            @foreach (['preferred_citation', 'corrections'] as $key)
                                @if (isset($publication->{$key}))
                                    <dt class="col-sm-3 text-sm-right">{{ __('opes.' . $key) }}:</dt>
                                    <dd class="col-sm-9">
                                        {{ $publication->{$key} }}
                                    </dd>
                                @endif
                            @endforeach
                        </dl>
                    </li>
                @endforeach
            </ul>

            <h4 class="mt-3">Bibliography</h4>

            @if (empty($record->bibliography))
                <span class="text-muted">–</span>
            @else
                <ul><li>{{ $record->bibliography }}</li></ul>
            @endif

        </div>
    </div>
@endsection

