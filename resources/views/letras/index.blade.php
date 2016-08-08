index.blade.php
@extends('layouts.letras')

@section('content')

       
     @can('letras')
            <p>
                <a href="{{ action('LetrasController@create') }}"><i class="fa fa-file"></i> Opprett ny post</a>
                &nbsp;
                
            </p>
        @endif
        

       <div class="panel panel-default">
            <div class="panel-body">

                @include('letras.search')

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
                        {!! $record->sjanger() !!}
                </td>
                <td>
                        {!! $record->utgivelsesaar() !!}
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
