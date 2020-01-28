@extends('litteraturkritikk.layout')

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">{{ trans('messages.lists') }}</li>
        </ol>
    </nav>

    <ul>
        @foreach ($lists as $list)
            <li>
                <a href="{{ $base->action('listShow', ['id' => $list->id]) }}">{{ $list->label }}</a>
            </li>
        @endforeach
    </ul>

@endsection
