@extends('layouts.dommer')

@section('content')

    @can('dommer')
        <p>
            <a href="{{ action('DommerController@create') }}"><em class="fa fa-file"></em> Opprett ny post</a>
            &nbsp;
            <a href="{{ action('PageController@edit', ['page' => 'dommer/intro']) }}"><em class="fa fa-edit"></em> Rediger introtekst</a>
        </p>
    @endif

    <p>
        {!! $intro !!}
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <search-form
                    action="{{ action('DommerController@index') }}"
                    :initial-query="{{ json_encode($processedQuery) }}"
                    :schema="{{ json_encode($schema) }}"
                    :advanced-search="{{ json_encode($advancedSearch) }}"
            ></search-form>
        </div>
    </div>

    <data-table
            v-once
            url="{{ action('DommerController@index') }}"
            prefix="dommer"
            :schema="{{ json_encode($schema) }}"
            :default-columns="{{ json_encode($defaultColumns) }}"
            :order="{{ json_encode($order) }}"
            :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"
    ></data-table>

@endsection
