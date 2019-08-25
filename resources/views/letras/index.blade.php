@extends('layouts.letras')

@section('content')

    @can('letras')
        <p>
            <a href="{{ action('LetrasController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
            &nbsp;
            <a href="{{ action('PageController@edit', ['page' => 'letras/intro']) }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
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
