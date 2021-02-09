@extends('base.show')


@section('record_header_extra_buttons')

@endsection

@section('actions')
    @if ($record->public)
        <form style="display: inline-block" action="{{ $base->action('unpublish', $record->{$schema->primaryId}) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-xs">
                {{ __('messages.unpublish') }}
            </button>
        </form>
    @else
        <form style="display: inline-block" action="{{ $base->action('publish', $record->{$schema->primaryId}) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-success btn-xs">
                {{ __('messages.publish') }}
            </button>
        </form>
    @endif
@endsection

@section('record')

    @if (!$record->public && !Auth::check())

        <div class="alert alert-danger">
            {{ __('base.status.unpublished') }}
        </div>

    @else

        @if (!$record->public)
            <div class="alert alert-warning">
                {{ __('base.status.unpublished_loggedin') }}
            </div>
        @endif

        <div class="mb-3">
            @if ($record->papyri_dclp_url)
                <a  href="{{ $record->papyri_dclp_url }}">
                    <em class="fa fa-globe"></em>
                    View record at papyri.info/DCLP
                </a>
            @endif
            @if ($record->trismegistos_url)
                <a  href="{{ $record->trismegistos_url }}">
                    <em class="fa fa-globe"></em>
                    View record at Trismegistos
                </a>
            @endif
        </div>


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
                    @if (!in_array($group->label, [__('opes.images'), __('opes.editions')]))
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
                                                <a href="{{ $base->action('index', ['q' => $field->key . ' eq ' . $value]) }}" class="badge badge-primary">{{ $value }}</a>
                                            @endforeach
                                        @else
                                            {!! $record->getFormattedValue($field->key, $record->{$field->key}, $base) !!}
                                        @endif
                                    </dd>
                                @endif
                            @endforeach
                        </dl>
                    @endif
                @endforeach

                <h4>{{ __('opes.editions') }}</h4>
                <ul class="list-group">
                    @foreach ($record->editions as $edition)
                        <li class="list-group-item">
                            {{ $edition }}
                            @if (count($edition->correctionsArray()))
                                <div>
                                    ▾ {{ __('opes.edition.corrections') }}:
                                    <ul>
                                    @foreach ($edition->correctionsArray() as $correction)
                                        <li>{{ $correction }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <h4 class="mt-3">Bibliography</h4>

                @if (empty($record->bibliographyArray()))
                    <span class="text-muted">–</span>
                @else
                    <ul class="list-group">
                        @foreach($record->bibliographyArray() as $item)
                            <li class="list-group-item">{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    @endif
@endsection

