<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class WorkSchema extends BaseSchema
{
    protected $schema = [
        "id" => "litteraturkritikk.verk",
        "fields" => [

            // Tittel
            [
                "key" => "verk_tittel",
                "type" => "autocomplete",

                "edit" => [
                    "placeholder" => "Tittel på den omtalte utgaven av verket",
                    "help" => "Skriv inn tittelen på den omtalte utgaven av verket slik den står skrevet på tittelbladet. Ikke bruk anførselstegn med mindre dette er en del av tittelen.<hr>Om utgave ikke spesifiseres, gjør følgende: For norske verk, før inn originalutgaven. For verk hvor originalutgaven ikke er norsk, sjekk om en norsk oversettelse forelå på omtalens publiseringstidspunkt. Hvis ja, før inn oversatt utgave, hvis nei, før inn originalutgaven.",
                ],
            ],

            // Språk
            [
                "key" => "verk_spraak",
                "type" => "select",
                "defaultValue" => [],
                "multiple" => true,

                "edit" => [
                    "preload" => true,
                    "allow_new_values" => true,
                    "placeholder" => "Språket den omtalte utgaven er skrevet på",
                    "help" => "Skriv inn språket den omtalte utgaven er skrevet på. Bruk liten forbokstav. For norsk, skriv bokmål eller nynorsk. Begynn å skrive inn aktuelt språk og velg fra listen som dukker opp. Trykk på «opprett [...]» om det aktuelle språket ikke finnes i listen. Flere verdier kan registreres ved behov, skriv da inn et språk av gangen, slik at hvert språk vises med en grå boks rundt.",
                ],
            ],

            // Utgivelsessted
            [
                "key" => "verk_utgivelsessted",
                "type" => "autocomplete",

                "edit" => [
                    "placeholder" => "Geografisk utgivelsessted for omtalt utgave",
                    "help" => "Skriv inn utgivelsessted, bruk samme skrivemåte som på tittelbladet, men skriv ut eventuelle forkortelser. Ved mer enn ett utgivelsessted, bruk «&» som skilletegn mellom stedene. Eksempel: Kristiania & København",
                ]
            ],

            // År
            [
                "key" => "verk_dato",
                "type" => "simple",

                "columnClassName" => "dt-body-nowrap",

                "search" => [

                    "sort_index" => "verk_dato_s",

                    "advanced" => true,
                    "type" => "range",
                    "widget" => "rangeslider",
                    "widgetOptions" => [
                        "minValue" => 1789,
                    ],
                    "operators" => [
                        Operators::IN_RANGE,
                        Operators::OUTSIDE_RANGE,
                        Operators::IS_NULL,
                        Operators::NOT_NULL,
                    ],
                ],
                "edit" => [
                    "placeholder" => "Utgivelsesår for omtalt utgave",
                    "help" => "Fyll inn utgivelsesår for den omtalte utgaven av verket. For utgivelsesår før år 0, skriv inn årstallet etterfulgt av mellomrom og 'fvt.' (inkludert punktum).",
                ],
            ],

            // Forfatter
            [
                "key" => "verk_forfatter",
                "type" => "entities",
                "entityType" => Person::class,
                "modelAttribute" => "forfattere",

                "relationship" => [
                    "table" => "litteraturkritikk_person_contributions",
                    "source_id" => "contribution_id",
                    "source_type" => "contribution_type",
                    "target_id" => "person_id",
                ],

               // "pivotTable" => "litteraturkritikk_record_person",
                //"pivotTableKey" => "person_id",
                "pivotFields" => [
                    [
                        "key" => "person_role",
                        "type" => "select",
                        "multiple" => true,
                        "defaultValue" => ["forfatter"],
                        "values" => [
                            ["value" => "forfatter", "prefLabel" => "forfatter"],
                            ["value" => "redaktør", "prefLabel" => "redaktør"],
                            ["value" => "gjendikter", "prefLabel" => "gjendikter"],
                        ],
                        "edit" => [
                            "allow_new_values" => false,
                            "help" => "Velg aktuell rolle fra listen som dukker opp når du klikker i feltet. Det er mulig å velge mer enn én rolle, men velg kun den eller de rollene som er aktuelle for det omtalte verket. Eksempelvis var B. Bjørnson både forfatter og kritiker, men i forbindelse med H. Jægers omtale av <em>Synnøve Solbakken</em> er Bjørnsons rolle kun forfatter.",
                        ],
                    ],
                    [
                        "key" => "pseudonym",
                        "type" => "simple",
                        "edit" => [
                            "help" => 'Fyll inn pseudonym om det er aktuelt for det omtalte verket, eksempelvis «Bernhard Borge» for André Bjerke.',
                        ]
                    ],
                    [
                        "key" => "kommentar",
                        "type" => "simple",
                        "edit" => [
                            "help" => 'Feltet brukes som et fotnotefelt, viktig tilleggsinformasjon kan legges her.',
                        ]
                    ],
                    [
                        "key" => "position",
                        "type" => "simple",
                        "edit" => false,
                    ],
                ],

                "search" => [
                    "widget" => "autocomplete",
                    "placeholder" => "Fornavn og/eller etternavn",
                    "type" => "ts",
                    "ts_index" => "forfatter_ts",
                    "operators" => [
                        Operators::CONTAINS,
                        Operators::NOT_CONTAINS,
                        Operators::IS_NULL,
                        Operators::NOT_NULL,
                    ],
                ],

                "edit" => [
                    "cssClass" => "col-md-12",
                    "help" => "Trykk på «Legg til» og begynn å skrive forfatterens navn i feltet. Om vedkommende finnes i personregisteret, velg personens navn fra listen som dukker opp. Om forfatteren ikke ligger i registeret, trykk på «Opprett ny» for å opprette personen."
                ],
            ],

            // mfl.
            [
                "key" => "verk_forfatter_mfl",
                "type" => "boolean",

                "showInTableView" => false,
                "showInRecordView" => false,
                "search" => false,

                // "default" => false,
                "edit" => [
                    "label" => "Flere forfattere",
                    "help" => "Kryss av her dersom det er flere forfattere enn det er hensiktsmessig å registrere. Forsøk alltid å få med forfatterne som er hovedfokus for omtalen.",
                    "cssClass" => "col-md-12",
                ],
            ],

            // Sjanger
            [
                "key" => "verk_sjanger",
                "type" => "select",
                "values" => [
                    ["value" => "lyrikk", "prefLabel" => "lyrikk"],
                    ["value" => "drama", "prefLabel" => "drama"],
                    ["value" => "fortelling", "prefLabel" => "fortelling"],
                    ["value" => "roman", "prefLabel" => "roman"],
                    ["value" => "kortprosa", "prefLabel" => "kortprosa"],
                    ["value" => "essay", "prefLabel" => "essay"],
                    ["value" => "tegneserie", "prefLabel" => "tegneserie"],
                    ["value" => "annen skjønnlitteratur", "prefLabel" => "annen skjønnlitteratur"],
                    ["value" => "biografi", "prefLabel" => "biografi"],
                    ["value" => "taler", "prefLabel" => "taler"],
                    ["value" => "brev", "prefLabel" => "brev"],
                    ["value" => "avhandling", "prefLabel" => "avhandling"],
                    ["value" => "litteraturhistorie", "prefLabel" => "litteraturhistorie"],
                    ["value" => "lærebok", "prefLabel" => "lærebok"],
                    ["value" => "annen sakprosa", "prefLabel" => "annen sakprosa"],
                ],

                "search" => [
                    "placeholder" => "Sjanger til det omtalte verket",
                ],
                "edit" => [
                    "allow_new_values" => false,
                    "placeholder" => "Sjanger til det omtalte verket",
                    "help" => "Fyll inn sjangeren til det omtalte verket. Velg verdi fra <a target=\"_blank\" href=\"/norsk-litteraturkritikk/veiledning#sjanger\">databasens egen sjangertypologi</a>.",
                ],
            ],

            // Kommentar
            [
                "key" => "verk_kommentar",
                "type" => "simple",

                "search" => [
                    "advanced" => true,
                ],
                "edit" => [
                    "placeholder" => "Fyll eventuelt inn annen relevant informasjon",
                    "help" => "Feltet fungerer som et fotnotefelt hvor annen relevant informasjon kan legges inn, eksempelvis informasjon om utgaven eller om anledningen for omtalen.",
                ],
            ],

            // Fulltekst-URL
            [
                "key" => "verk_fulltekst_url",
                "type" => "url",

                "search" => [
                    "advanced" => true,
                ],
                "edit" => [
                    "placeholder" => "Fyll inn lenke til fulltekstversjon dersom verket er tilgjengelig digitalt.",
                    "help" => "Kopier og lim inn lenke til fulltekst dersom verket er tilgjengelig digitalt, eksempelvis i Nasjonalbibliotekets digitale arkiv. URLer fra NB.no konverteres automatisk til varige lenker.<hr>Flere lenker skilles med mellomrom – ikke bruk semikolon her.",
                    "cssClass" => "col-md-12",
                ],
            ],
        ],

        "groups" => [

            // Omtale av
            [
                "key" => "originalutgave",
                "fields" => [

                    // Originaltittel
                    [
                        "key" => "verk_originaltittel",
                        "type" => "autocomplete",

                        "search" => [
                            "advanced" => true,
                            "placeholder" => "Søk kun i originaltitler",
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn hvis tittel på omtalt utgave avviker fra originaltittel, f.eks. ved oversettelse",
                            "help" => "Fyll inn om den omtalte utgavens tittel avviker fra originaltittelen, f.eks. ved oversettelser, senere utgaver med endret tittel, eller lignende.<hr>Ved originaltitler på russisk, japansk eller andre ikke-latinske alfabet, kontakt Anne Sæbø ved UB for å få standardisert originaltittel tilsendt fra aktuell fagreferent. Se <a href=\"/norsk-litteraturkritikk/veiledning#originaltittel\" target=\"_blank\">redigeringsveiledning</a> for mer informasjon.",
                        ],

                    ],

                    // Originalspråk
                    [
                        "key" => "verk_originalspraak",
                        "type" => "select",
                        "multiple" => true,
                        "defaultValue" => [],

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "advanced" => true,
                            "placeholder" => "Språket originalutgaven er skrevet på",
                        ],
                        "edit" => [
                            "preload" => true,
                            "allow_new_values" => true,
                            "placeholder" => "Språket originalutgaven er utgitt på",
                            "help" => "Fyll inn språket originalutgaven er skrevet på. Feltet skal bare brukes hvis feltet «Originaltittel» er fylt ut. Bruk liten forbokstav. For norsk, bruk «bokmål» eller «nynorsk». Begynn å skrive inn aktuelt språk og velg fra listen som dukker opp. Trykk på «opprett [...]» om det aktuelle språket ikke finnes i listen. Flere verdier kan registreres ved behov, skriv da inn et språk av gangen, slik at hvert språk vises med en grå boks rundt.",
                        ]
                    ],

                    // Originaltittel (transkribert)
                    [
                        "key" => "verk_originaltittel_transkribert",
                        "type" => "autocomplete",
                        "search" => [
                            "advanced" => true,
                            "placeholder" => "Søk kun i transkriberte titler",
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn hvis originaltittel bruker ikke-latinsk skrift",
                            "help" => "Ved originaltitler på russisk, japansk eller andre ikke-latinske alfabet, kontakt Anne Sæbø ved UB for å få transkribert originaltittel tilsendt fra aktuell fagreferent. Se <a href=\"/norsk-litteraturkritikk/veiledning#originaltittel_transkribert\" target=\"_blank\">redigeringsveiledning</a> for mer informasjon."
                        ],
                    ],


                    // Original utgivelsesdato
                    [
                        "key" => "verk_originaldato",
                        "type" => "simple",

                        "columnClassName" => "dt-body-nowrap",

                        "search" => [

                            "sort_index" => "verk_originaldato_s",

                            "advanced" => true,
                            "type" => "range",
                            "widget" => "rangeslider",
                            "widgetOptions" => [
                                "minValue" => -500,
                            ],
                            "operators" => [
                                Operators::IN_RANGE,
                                Operators::OUTSIDE_RANGE,
                                Operators::IS_NULL,
                                Operators::NOT_NULL,
                            ],
                        ],
                        "edit" => [
                            "placeholder" => "Utgivelsesår for originalutgave",
                            "help" => "Fyll inn utgivelsesår for originalutgaven av verket. Feltet skal bare brukes hvis feltet «Originaltittel» er fylt ut. For utgivelsesår før år 0, skriv inn årstallet etterfulgt av mellomrom og 'fvt.' (inkludert punktum).",
                        ],
                    ],
                ],
            ],
        ],
    ];
}
