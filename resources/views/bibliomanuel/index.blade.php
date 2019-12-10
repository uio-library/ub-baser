@extends('bibliomanuel.layout')

@section('content')

    @can($base->id)
        <p>
            <a href="{{ $base->action('create') }}"><em class="fa fa-file"></em> Opprett ny post</a>
            <a href="{{ $base->pageAction('intro', 'edit') }}"><em class="fa fa-edit"></em> Rediger introtekst</a>
        </p>
    @endcan

    <p>
        {!! $intro !!}
        <img src="/images/IMG_0483.JPG" id="bibliomanuel_illustrasjon_jpg" alt="bibliografi Manuel">
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <search-form
                :initial-query="{{ json_encode($processedQuery) }}"
                :schema="{{ json_encode($schema) }}"
                :settings="{{ json_encode($settings) }}"
                :advanced-search="{{ json_encode($advancedSearch) }}"
            ></search-form>
        </div>
    </div>

    <data-table
        v-once
        prefix="{{ $base->id }}"
        base-url="{{ $base->action('index') }}"
        :schema="{{ json_encode($schema) }}"
        :default-columns="{{ json_encode($defaultColumns) }}"
        :order="{{ json_encode($order) }}"
        :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"
    ></data-table>

@endsection
