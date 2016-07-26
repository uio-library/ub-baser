index.blade.php
@extends('layouts.letras')

@section('content')

        <p>
            @if (Auth::check())
                <a href="{{ action('LetrasTableController@index') }}"><i class="fa fa-table"></i> Tabellvisning</a>
                &nbsp;
            @endif
            @can('letras')
                <a href="{{ action('LetrasController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
                &nbsp;
                <a href="{{ route('letras.intro.edit') }}"><i class="fa fa-edit"></i> Rediger introtekst</a>
            @endif
          
           {{ $records->total() }} poster
        </p>

        


      <div class="panel panel-default">
            <div class="panel-body">

                <form class="form-horizontal" id="searchForm" method="GET" action="{{ action('LetrasController@index') }}">

                    <input type="hidden" name="search" value="true">

                    <div class="form-group">
                        <label for="navn" class="col-sm-2 control-label">{{ trans('letras.forfatter') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="forfatter" name="forfatter" value="{{ array_get($query, 'forfatter') }}">
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
              <i class="zmdi zmdi-sort-amount-asc"></i>
            @else
              <i class="zmdi zmdi-sort-amount-desc"></i>
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
                    <a href="{{ action('LetrasController@show', $record->id) }}">

                        {!! $record->forfatter() !!}
                    </a>
                </td>
                <td>
                        {!! $record->tittel() !!}
                </td>
                <td>
                        {!! $record->utgivelsesaar() !!}
                </td>
                <td>
                        {!! $record->sjanger() !!}
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
