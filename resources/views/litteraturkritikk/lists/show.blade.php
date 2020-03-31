@extends('litteraturkritikk.layout')

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ $base->action('listIndex') }}">{{ trans('messages.lists') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $list->label }}</li>
        </ol>
    </nav>

    <table class="table">
        <tr>
            <th>
                @if ($sort === 'value')
                    <em class="fa fa-sort-alpha-asc"></em>
                    Verdi
                @else
                    <a href="{{ $base->action('listShow', ['id' => $list->id, 'sort' => 'value']) }}">
                        Verdi
                    </a>
                @endif
            </th>
            <th class="text-nowrap">
                @if ($sort === 'record_count')
                    <em class="fa fa-sort-numeric-desc"></em>
                    Antall poster
                @else
                    <a href="{{ $base->action('listShow', ['id' => $list->id, 'sort' => 'record_count']) }}">
                        Antall poster
                    </a>
                @endif
            </th>
        </tr>
        @foreach ($aggs as $agg)
        <tr>
            <td>
                @if (is_null($agg->value))
                    (mangler verdi)
                @else
                    {{ $agg->value }}</td>
                @endif
            <td class="text-nowrap">
                <a href="{{ $base->action('index', [
                    'q' => $list->field . ' eq ' . $agg->value,
                ]) }}">{{ $agg->record_count }} poster</a>
            </td>
        </tr>
        @endforeach
    </table>


@endsection
