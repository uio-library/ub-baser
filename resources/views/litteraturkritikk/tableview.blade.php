@extends('layouts.litteraturkritikk')

@section('content')

    @include('table-view::container', ['tableView' => $tableView])

@endsection

@section('script')

    @include('table-view::scripts')

@endsection
