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
        </p>

        
        <p>
           {{ $records->total() }} poster
        </p>

        

       <div class="container">  
    <table border="0" cellpadding="0" cellspacing="0" class="table table-striped">
    @if(count($records)>0) {{-- If there is data then display it --}}
    <thead>
      <tr>
        <th>Forfatter</th>
        <th>Tittel</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($records as $record)
    
    <tr>
      <td>
                    <a href="{{ action('LetrasController@show', $record->id) }}">
                        {{ $record->forfatter}}
                    </a>
                </td>
      <td>{{ $record->tittel}}</td>
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
