@extends('layouts.bibsys')

@section('content')

    @can('bibsys')
        <p>
            <a href="{{ action('PageController@edit', ['page' => 'bibsys/intro']) }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
        </p>
    @endif

    <p>
        {!! $intro !!}
    </p>

     <div class="panel panel-default">
        <div class="panel-body">
            <search-form
                action="{{ action('BibsysController@index') }}"
                :initial-query="{{ json_encode($processedQuery) }}"
                :schema="{{ json_encode($schema) }}"
                :advanced-search="{{ json_encode($advancedSearch) }}"
            ></search-form>
        </div>
     </div>

     <data-table
             v-once
             url="{{ action('BibsysController@index') }}"
             prefix="bibsys"
             :schema="{{ json_encode($schema) }}"
             :default-columns="{{ json_encode($defaultColumns) }}"
             :order="{{ json_encode($order) }}"
             :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"
     ></data-table>

@endsection
