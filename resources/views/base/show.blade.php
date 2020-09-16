@extends($base->id . '.layout')

@section('content')

    <div class="mb-1">
        <small class="text-muted">
            @section('record_header')
                <a href="{{ $base->action('prev', $currentQuery) }}">« {{ __('messages.previous_record') }}</a>
                |
                <a href="{{ $base->action('index', $currentQuery) }}">{{ __('messages.back_to_search') }}</a>
                |
                <a href="{{ $base->action('next', $currentQuery) }}">{{ __('messages.next_record') }} »</a>
                @if (count($order))
                    (sortert etter
                    @foreach ($order as $o)
                        {{ mb_strtolower(__("{$base->id}.{$o['key']}")) }}
                        ({{ __("messages.{$o['direction']}") }})
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach)
                @endif
            @show
        </small>
    </div>

    <div>
        <div class="pb-2 mt-2 mb-3">
            <div class="d-flex align-items-end">
                <div>
                    <h2 class="mb-0 record-title">
                        {{ $record->getTitle() }}
                    </h2>
                </div>
                <div class="flex-grow-1 text-sm-right text-muted">
                    @section('record_toolbar')
                        @can($base->id)

                            <a href="{{ $base->action('edit', $record->{$schema->primaryId}) }}" class="btn btn-outline-primary">
                                <em class="fa fa-edit"></em>
                                {{ __('messages.edit') }}
                            </a>

                            @if ($base->usesSoftDeletes() && $record->trashed())
                                <form style="display: inline-block" action="{{ $base->action('restore', $record->{$schema->primaryId}) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-xs">
                                        <em class="fa fa-undo"></em>
                                        {{ __('messages.restore') }}
                                    </button>
                                </form>
                            @else
                                <form style="display: inline-block" action="{{ $base->action('destroy', $record->{$schema->primaryId}) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-outline-danger btn-xs">
                                        <em class="fa fa-trash"></em>
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            @endif

                            @yield('actions')

                        @endcan
                    @show
                </div>
            </div>
        </div>

        <div class="record">
            @if ($base->usesSoftDeletes() && $record->trashed())
                <div class="alert alert-danger">
                    {{ __('base.status.recordtrashed') }}
                </div>
            @endif

            @yield('record')
        </div>
    </div>

@endsection
