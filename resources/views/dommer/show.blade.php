@extends('dommer.layout')

@section('content')

@if ($record->trashed())
    <div class="alert alert-danger">
        {{ __('base.status.recordtrashed') }}
    </div>
@endif

<div>
	<a href="{{ URL::previous() }}" class="btn btn-link">
        <em class="fa fa-arrow-circle-left"></em>
        Tilbake
    </a>

	@can('dommer')
        <a href="{{ $base->action('edit', $record->id) }}" class="btn btn-link">
            <em class="fa fa-edit"></em>
            Rediger post
        </a>

        @if ($record->trashed())
            <form style="display: inline-block" action="{{ $base->action('restore', $record->id) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-link text-danger btn-xs">
                    <em class="fa fa-undo"></em>
                    Gjenopprett
                </button>
            </form>
        @else
            <form style="display: inline-block" action="{{ $base->action('destroy', $record->id) }}" method="post">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-link text-danger btn-xs">
                    <em class="fa fa-trash"></em>
                    Slett
                </button>
            </form>
        @endif
    @endcan
</div>

<h2>
	«{{ $record->navn }}»
</h2>

<p>
	Referanse: {{ $record->kilde }} {{ $record->aar }}, side {{ $record->side }}
</p>

@endsection
