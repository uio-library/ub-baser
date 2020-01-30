<span class="ltk_person">
    @spaceless

    <a href="{{ $base->action('PersonController@show', $record->id) }}"><i class="fa fa-user"></i>
        {{ strval($record) }}</a>

    @if ($record->pivot->pseudonym)
        <span>, under pseudonymet «{{ $record->pivot->pseudonym }}»</span>
    @endif

    @if ($record->pivot->person_role && !in_array($record->pivot->person_role, [['kritiker'], ['forfatter']]))
        <span> ({{ implode(', ', $record->pivot->person_role) }})</span>
    @endif

    @if ($record->pivot->kommentar)
        <span> ({{ $record->pivot->kommentar }})</span>
    @endif

    @endspaceless
</span>

