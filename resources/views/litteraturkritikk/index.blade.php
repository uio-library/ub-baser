@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            @can('litteraturkritikk')
                <a href="{{ action('LitteraturkritikkController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
                &nbsp;
                <a href="{{ action('PageController@edit', ['page' => 'norsk-litteraturkritikk/intro']) }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
            @endcan
        </p>

        <div class="lead">
            {!! $intro !!}
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <search-form
                        action="{{ action('LitteraturkritikkController@index') }}"
                        :initial-query="{{ json_encode($processedQuery) }}"
                        :schema="{{ json_encode($schema) }}"
                        :advanced-search="{{ json_encode($advancedSearch) }}"
                ></search-form>
            </div>
        </div>

        <data-table
                v-once
                url="{{ action('LitteraturkritikkController@index') }}"
                prefix="litteraturkritikk"
                :schema="{{ json_encode($schema) }}"
                :default-columns="{{ json_encode($defaultColumns) }}"
                :order="{{ json_encode($order) }}"
                :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"
        ></data-table>

@endsection
