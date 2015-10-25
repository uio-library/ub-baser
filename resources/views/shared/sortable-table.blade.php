<table class="table table-striped">
	<thead>
		<tr>
			@foreach ($columns as $column)
			<th>
				<a href="{{ $column['link'] }}">
					{{ $column['label'] }}
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
			@foreach ($columns as $column)
			<td>
				<a href="{{ $record->link() }}">
					{{ $record->{$column['field']} }}
				</a>
			</td>
			@endforeach
		</tr>
	@endforeach
	</tbody>
</table>

{!! $records->appends(['sort' => $sortColumn, 'order' => $sortOrder])->render() !!}
