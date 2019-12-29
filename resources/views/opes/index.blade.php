index.blade.php
@extends('opes.layout')

@section('content')

        <p>

            @can('opes')
                <a href="{{ action('Controller@create') }}"><em class="fa fa-file"></em> Opprett ny post</a>


            @endif


        </p>




      <div class="panel panel-default">
            <div class="panel-body">

                <form class="form-horizontal" id="searchForm" method="GET" action="{{ action('Controller@index') }}">

                    <input type="hidden" name="search" value="true">

                    <div class="form-group">
                        <label for="inv_no" class="col-sm-2 control-label">{{ trans('opes.inv_no') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inv_no" name="inv_no" value="{{ array_get($query, 'inv_no') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type_of_text_file" class="col-sm-2 control-label">{{ trans('opes.type_of_text_file') }}</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="type_of_text_file" name="type_of_text_file" value="{{ array_get($query, 'type_of_text_file') }}">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="ti" class="col-sm-2 control-label"></label>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-block"><em class="zmdi zmdi-search"></em> {{ trans('messages.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




       <div class="container">
    <table border="0" cellpadding="0" cellspacing="0" class="table table-striped">
    @if(count($records)>0) {{-- If there is data then display it --}}
    <thead>
    <tr>
      @foreach ($columns as $column)
      <th style="{{ isset($column['width']) ? 'width: ' . $column['width'] . ';' : '' }}">
        <a href="{{ $column['link'] }}">
          {{ trans($prefix . '.' . $column['field']) }}
          @if ($sortColumn == $column['field'])
            @if ($sortOrder == 'asc')
              <em class="zmdi zmdi-sort-amount-asc"></em>
            @else
              <em class="zmdi zmdi-sort-amount-desc"></em>
            @endif
          @endif
        </a>
      </th>
      @endforeach
    </tr>
  </thead>
    <tbody>
    @foreach ($records as $record)

    <tr>
                <td>
                    <a href="{{ action('Controller@show', $record->id) }}">

                    {{$record}}


                        {{ $record->inv_no }}
                    </a>
                </td>
                <td>
                        {{ $record->type_of_text_file }}
                </td>

    </tr>
    @endforeach
    </tbody>
    <tr>
      <td colspan="2">
        <div class="pagination">{!! str_replace('/?', '?', $records->render()) !!}</div>
      </td>
    </tr>
    @else
    <tr>
      <td>No record found</td>
    </tr>
    @endif
    </table>
  </div>
</body>



@endsection
