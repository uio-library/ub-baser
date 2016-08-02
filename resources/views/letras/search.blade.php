

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
                    <div class="form-group">
                        <label for="tittel" class="col-sm-2 control-label">{{ trans('letras.tittel') }}</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="tittel" name="tittel" value="{{ array_get($query, 'tittel') }}">
                        </div>
                        <label for="utgivelsesaar" class="col-sm-2 control-label">{{ trans('letras.utgivelsesaar') }}</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="utgivelsesaar" name="utgivelsesaar" value="{{ array_get($query, 'utgivelsesaar') }}">
                        </div>
                        <label for="sjanger" class="col-sm-2 control-label">{{ trans('letras.sjanger') }}</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="sjanger" name="sjanger" value="{{ array_get($query, 'sjanger') }}">
                        </div>
                    </div>
                    <div class="form-group" id="searchButtonContainer">
                        
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-block"><i class="zmdi zmdi-search"></i> {{ trans('messages.search') }}</button>
                        </div>
                        <div class="col-sm-2">
                            <a href="{{ action('LetrasController@index') }}" class="btn btn-default">{{ trans('messages.clear') }}</a>
                        </div>
                    </div>                  
                </form>
            </div>
        </div>
  