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

        <!--
        kritikktype kommentar spraak tittel publikasjon utgivelsessted aar dato aargang nummer bind hefte sidetall
        utgivelseskommentar kritiker_etternavn kritiker_fornavn kritiker_kjonn kritiker_pseudonym kritiker_kommentar
        forfatter_etternavn forfatter_fornavn forfatter_kjonn forfatter_kommentar verk_tittel verk_aar verk_sjanger
        verk_kommentar verk_utgivelsessted
-->

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
                    <li>m. fl.</li>
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


        <h3>Omtalt verk</h3>

        @if (count($record->forfattere))
        <ul class="authorlist">
        @foreach ($record->forfattere as $person)
            <li><a href="{{ action('LitteraturkritikkPersonController@show', $person->id) }}">{{ strval($person) }}</a>{{
                ($person->pivot->person_role == 'redaktør') ? ' (red.)' : ''
            }}{{
                $person->pseudonym_for ? ' (pseudonym for: ' . $person->pseudonym_for . ')' : ''
            }}{{
                $person->pseudonym ? ' (pseudonym: ' . $person->pseudonym . ')' : ''
            }}</li>
            @endforeach
            @if ($record->forfatter_mfl)
                <li>m. fl.</li>
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
                <dd>{{ $record->created_at }} av {{ $record->created_by ?: ' (import)' }}</dd>
                <dt>Sist endret</dt>
                <dd>{{ $record->updated_at }} av {{ $record->updated_by ?: ' (import)' }}</dd>
            </dl>
        @endif

@endsection
