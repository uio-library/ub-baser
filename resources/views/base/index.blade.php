@extends($base->id . '.layout')

@section('content')

    @can($base->id)
        <p>
            @section('base_actions')
                <a href="{{ $base->action('create') }}"><em class="fa fa-file"></em> Opprett ny post</a>
                <a href="{{ $base->pageAction('intro', 'edit') }}"><em class="fa fa-edit"></em> Rediger introtekst</a>
            @show
        </p>
    @endcan

    {!! $intro !!}

    <search-page
        :schema="{{ json_encode($schema) }}"

        :initial-query="{{ json_encode($processedQuery) }}"
        :settings="{{ json_encode($settings) }}"
        :advanced-search="{{ json_encode($advancedSearch) }}"

        results-component="data-table"
        base-id="{{ $base->id }}"
        base-url="{{ $base->action('index') }}"
        :default-columns="{{ json_encode($defaultColumns) }}"

        :initial-order="{{ json_encode($order) }}"
        :default-order="{{ json_encode($defaultOrder) }}"
    ></search-page>

@endsection
