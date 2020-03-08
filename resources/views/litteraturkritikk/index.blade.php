@extends('base.index')

@section('base_actions')
    @parent
    <a href="{{ $base->action('listIndex') }}"><em class="fa fa-list"></em> Lister</a>
@endsection

