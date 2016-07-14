@extends('layouts.litteraturkritikk')

@section('content')

        <p>
            <a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-left"></i> Tilbake</a>
            @can('litteraturkritikk')
            &nbsp;
            <a href="{{ action('LitteraturkritikkController@edit', $record->id) }}"><i class="fa fa-edit"></i> Rediger post</a>
            @endif
        </p>

        <h2>
            Post {{ $record->id }}
        </h2>

        <h3>Kritikken</h3>

        @if (count($record->kritikere))
            <ul class="authorlist">
                @foreach ($record->kritikere as $person)
                    <li><a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                        $person->pseudonym_for ? ' (pseudonym for: ' . $person->pseudonym_for . ')' : ''
                    }}{{
                        $person->pseudonym ? ' (pseudonym: ' . $person->pseudonym . ')' : ''
                    }}</li>
                @endforeach
                @if ($record->kritiker_mfl)
                    <li class="mfl"><em>m. fl</em></li>
                @endif
            </ul>.
        @endif
        {{
            $record->tittel ? '«' . $record->tittel . '»' : ''
        }}{{
            count($record->kritikktype) ? ' (' . implode(', ', $record->kritikktype) . ')' : ''
        }}{!!
            $record->publikasjon ? '. I: <em>' . $record->publikasjon . '</em>' : ''
        !!}{{
            $record->utgivelsessted ? ' ' . $record->utgivelsessted . '' : ''
        }}{{
            $record->aargang ? ' årg. ' . $record->aargang : ''
        }}{{
            $record->bind ? ' bind ' . $record->bind : ''
        }}{{
            $record->aar ? ' (' . $record->aar . ')' : ''
        }}{{
            $record->nummer ? ' nummer ' . $record->nummer : ''
        }}{{
            $record->hefte ? ' hefte ' . $record->hefte : ''
        }}{{
            $record->sidetall ? ' ' . $record->sidetall : ''
        }}.{{
            $record->utgivelseskommentar ? ' ' . $record->utgivelseskommentar . '.' : ''
        }}{{
            $record->kommentar ? ' Kommentar: ' . $record->kommentar . '.' : ''
        }}


        <h3>Omtalt</h3>

        @if (count($record->forfattere))
        <ul class="authorlist">
        @foreach ($record->forfattere as $person)
            <li><a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                ($person->pivot->person_role != 'forfatter') ? ' (' . $person->pivot->person_role . ')' : ''
            }}{{
                $person->pseudonym_for ? ' (pseudonym for: ' . $person->pseudonym_for . ')' : ''
            }}{{
                $person->pseudonym ? ' (pseudonym: ' . $person->pseudonym . ')' : ''
            }}</li>
            @endforeach
            @if ($record->forfatter_mfl)
                <li class="mfl"><em>m. fl</em></li>
            @endif
        </ul>.
        @endif
        {{
            $record->verk_tittel ? '«' . $record->verk_tittel . '»' : ''
        }}{{
            $record->verk_sjanger ? " ($record->verk_sjanger)" : ''
        }}{{
            $record->verk_tittel ? '. ' : ''
        }}{{
            $record->verk_utgivelsessted ? $record->verk_utgivelsessted . ' ' : ''
        }}{{
            $record->verk_aar ? $record->verk_aar : ''
        }}

        @if ($record->verk_kommentar)
            Kommentar: {{ $record->verk_kommentar }}
        @endif

        @if (Auth::check())
            <h3>Metadata</h3>
            <dl class="dl-horizontal">
                <dt>Opprettet</dt>
                <dd>{{ $record->created_at }} av {{ $record->createdBy ? $record->createdBy->name : ' (import)' }}</dd>
                <dt>Sist endret</dt>
                <dd>{{ $record->updated_at }} av {{ $record->updatedBy ? $record->updatedBy->name : ' (import)' }}</dd>
            </dl>
        @endif

@endsection
