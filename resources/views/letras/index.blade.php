@extends('layouts.letras')

@section('content')

    @can('letras')
        <p>
            <a href="{{ action('LetrasController@create') }}"><em class="fa fa-file"></em> Opprett ny post</a>
            &nbsp;
            <a href="{{ action('PageController@edit', ['page' => 'letras/intro']) }}"><em class="fa fa-edit"></em> Rediger introtekst</a>
        </p>
    @endif

    <p>
        {!! $intro !!}
    </p>

     <div class="panel panel-default">
        <div class="panel-body">
            <search-form
                action="{{ action('LetrasController@index') }}"
                :initial-query="{{ json_encode($processedQuery) }}"
                :schema="{{ json_encode($schema) }}"
                :advanced-search="{{ json_encode($advancedSearch) }}"
            ></search-form>
        </div>
     </div>

     <data-table
             v-once
             url="{{ action('LetrasController@index') }}"
             prefix="letras"
             :schema="{{ json_encode($schema) }}"
             :default-columns="{{ json_encode($defaultColumns) }}"
             :order="{{ json_encode($order) }}"
             :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"
     ></data-table>

@endsection
