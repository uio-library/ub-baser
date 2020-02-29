@extends('base.show')

@section('record')

<p>
	Referanse: {{ $record->kilde }} {{ $record->aar }}, side {{ $record->side }}
</p>

@endsection
