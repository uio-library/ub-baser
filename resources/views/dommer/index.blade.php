@extends('layouts.dommer')

@section('content')

        <tt>index.blade.php</tt>

        <h2>
            Dommers populærnavn
        </h2>

        @can('dommer')
            <p>
                <a href="{{ action('DommerController@create') }}">+ Opprett ny post</a>
            </p>
        @endif

        <p>
            Dommenes populærnavn eller kallenavn er funnet i den juridiske litteraturen og i domsavsigelser hvor avgjørelsene er omtalt. Enkelte avgjørelser er oppført med flere populærnavn. Basen gir referanser til Norsk retstidende (Rt.), Rettens gang (RG) og Nordiske domme i sjøfartanliggende (ND). Basen utvikles av Juridisk bibliotek, Universitetet i Oslo
        </p>
        <p>
            Forslag til dommer som du mener burde være med i basen, kan sendes til ujur@ub.uio.no med angivelse av i hvilken sammenheng det aktuelle populærnavnet har vært eller vil bli brukt.
        </p>

        <div class="panel panel-default">
            <div class="panel-body">

                <form class="form-horizontal" id="searchForm" method="GET" action="{{ action('DommerController@index') }}">

                    <input type="hidden" name="search" value="true">

                    <div class="form-group">
                        <label for="navn" class="col-sm-2 control-label">{{ trans('dommer.navn') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="navn" name="navn" value="{{ array_get($query, 'navn') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kilde_id" class="col-sm-2 control-label">{{ trans('dommer.kilde_navn') }}</label>
                        <div class="col-sm-4">
                            {!! Form::select('kilde_id', $kilder, array_get($query, 'kilde_id'), ['placeholder' => trans('messages.choose'), 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="aar">{{ trans('dommer.aar') }}</label>
                            <input type="number" class="form-control" id="aar" name="aar" placeholder="{{ trans('dommer.aar') }}" value="{{ array_get($query, 'aar') }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label sr-only" for="side">{{ trans('dommer.side') }}</label>
                            <input type="number" class="form-control" id="side" name="side" placeholder="{{ trans('dommer.side') }}" value="{{ array_get($query, 'side') }}">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-block">{{ trans('messages.search') }}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>


        @include('shared.sortable-table')

@endsection

@section('script')

<script type="text/javascript">

$(function() {


});

</script>

@endsection