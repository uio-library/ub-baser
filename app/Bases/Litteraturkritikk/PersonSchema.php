<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\Schema as BaseSchema;

class PersonSchema extends BaseSchema
{
    protected $schema = [
        'id' => 'litteraturkritikk.person',
        'fields' => [
            [
                'key' => 'etternavn',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Skriv inn etternavn. Ved forfatternavn som ikke bruker det latinske alfabetet, eksempelvis russiske navn, kontakt Anne Sæbø ved UB for å få transkribert navn tilsendt fra aktuell fagreferent. Se <a href="/norsk-litteraturkritikk/veiledning#forfatter">redigeringsveiledning</a> for mer informasjon.',
                ],
            ],
            [
                'key' => 'fornavn',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Fyll inn fornavn og eventuelle mellomnavn.',
                ],
            ],
            [
                'key' => 'kjonn',
                'type' => 'select',
                'values' => [
                    ['value' => 'u', 'prefLabel' => 'ukjent'],
                    ['value' => 'm', 'prefLabel' => 'mann'],
                    ['value' => 'f', 'prefLabel' => 'kvinne'],
                ],
                'edit' => [
                    'help' => 'Velg aktuell kategori fra listen som dukker opp når du klikker i feltet.',
                ],
            ],
            [
                'key' => 'fodt',
                'type' => 'simple',
                'edit' => [
                    'help' => 'For å skille fra andre personer med samme navn. Valgfritt.',
                    'placeholder' => 'YYYY (valgfritt)',
                ],
            ],
            [
                'key' => 'dod',
                'type' => 'simple',
                'edit' => [
                    'help' => 'For å skille fra andre personer med samme navn. Valgfritt.',
                    'placeholder' => 'YYYY (valgfritt)',
                ],
            ],
            [
                'key' => 'bibsys_id',
                'type' => 'simple',
                'edit' => [
                    'help' => 'Identifikator i Felles autoritetsregister.',
                    'placeholder' => '(For fremtidig bruk)',
                ],
            ],
            [
                'key' => 'wikidata_id',
                'type' => 'simple',
                'edit' => [
                    'help' => 'Identifikator i Wikidata.',
                    'placeholder' => '(For fremtidig bruk)',
                ],
            ],
        ],
    ];
}
