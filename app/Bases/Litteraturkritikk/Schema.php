<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "litteraturkritikk",
        "fields" => [

            // ID
            [
                "key" => "id",
                "type" => "incrementing",
            ],

            // Søk i alle felt
            [
                "key" => "q",
                "type" => "search_only",

                "search" => [
                    "widget" => "simple",
                    "placeholder" => "Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.",
                    "type" => "ts",
                    "ts_index" => "any_field_ts",
                    "operators" => [Operators::CONTAINS, Operators::NOT_CONTAINS],
                ],
            ],

            // Person-søk (forfatter eller kritiker)
            [
                "key" => "person",
                "type" => "search_only",

                "search" => [
                    "placeholder" => "Fornavn og/eller etternavn",
                    "type" => "ts",
                    "ts_index" => "person_ts",
                    "operators" => [Operators::CONTAINS, Operators::NOT_CONTAINS],
                ],
            ],
        ],

        "groups" => [

            // Kritikken
            [
                "key" => "kritikken",
                "fields" => [

                    // Kritiker
                    [
                        "key" => "kritiker",
                        "type" => "entities",
                        "entityType" => Person::class,
                        "modelAttribute" => "kritikere",

                        "relationship" => [
                            "table" => "litteraturkritikk_person_contributions",
                            "source_id" => "contribution_id",
                            "source_type" => "contribution_type",
                            "target_id" => "person_id",
                        ],

                        //"pivotTable" => "litteraturkritikk_record_person",
                        //"pivotTableKey" => "person_id",
                        // "personRole" => "kritiker",
                        "pivotFields" => [
                            [
                                "key" => "person_role",
                                "type" => "select",
                                "multiple" => true,
                                "defaultValue" => ["kritiker"],
                                "values" => [
                                    ["value" => "kritiker", "prefLabel" => "kritiker"],
                                    ["value" => "redaktør", "prefLabel" => "redaktør"],
                                ],
                                "edit" => [
                                    "allow_new_values" => false,
                                ],
                            ],
                            [
                                "key" => "pseudonym",
                                "type" => "simple",
                                "edit" => [
                                    "help" => 'Skriv inn pseudonym om det er aktuelt for denne kritikken, eksempelvis «En Kvinderøst» for Mathilde Schjøtt.',
                                ]
                            ],
                            [
                                "key" => "kommentar",
                                "type" => "simple",
                                "edit" => [
                                    "help" => 'Dette feltet brukes som et fotnotefelt, viktig tilleggsinformasjon kan legges her.',
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
                            "ts_index" => "kritiker_ts",
                            "operators" => [
                                Operators::CONTAINS,
                                Operators::EQUALS,
                                Operators::IS_NULL,
                                Operators::NOT_NULL,
                            ],
                        ],
                        "edit" => [
                            "help" => "Trykk på «Legg til» og begynn å skrive kritikerens navn i feltet. Om vedkommende finnes i personregisteret, velg personens navn i listen som dukker opp i feltet. Om kritikeren ikke ligger i registeret, trykk «Opprett ny» for å opprette personen."
                        ],
                    ],

                    // mfl.
                    [
                        "key" => "kritiker_mfl",
                        "type" => "boolean",
                        // "default" => false,
                        "showInTableView" => false,
                        "showInRecordView" => false,
                        "search" => false,
                        "edit" => [
                            "label" => "Flere kritikere",
                            "help" => "Kryss av her dersom kritikken er signert av flere kritikere enn det er hensiktsmessig å registrere.",
                        ],
                    ],

                    // Publikasjon
                    [
                        "key" => "publikasjon",
                        "type" => "autocomplete",

                        "search" => [
                            "placeholder" => "Navn på tidsskrift, avis e.l.",
                            "type" => "simple",
                        ],
                        "edit" => [
                            "placeholder" => "Tittel på publikasjon",
                            "help" => "Fyll inn tittelen på publikasjon kritikken er publisert i. Merk at publikasjoner her omfatter både trykte og digitale medier, som aviser, tidsskrifter, bøker, TV- og radioprogrammer og nettsteder.",
                        ],
                    ],

                    // Medieformat
                    [
                        "key" => "medieformat",
                        "type" => "select",
                        "columnClassName" => "dt-body-nowrap",
                        "values" => [
                            ["value" => "avis", "prefLabel" => "avis"],
                            ["value" => "tidsskrift", "prefLabel" => "tidsskrift"],
                            ["value" => "bok", "prefLabel" => "bok"],
                            ["value" => "radio", "prefLabel" => "radio"],
                            ["value" => "tv", "prefLabel" => "tv"],
                            ["value" => "video", "prefLabel" => "video"],
                            ["value" => "blogg", "prefLabel" => "blogg"],
                            ["value" => "podcast", "prefLabel" => "podcast"],
                            ["value" => "nettmagasin", "prefLabel" => "nettmagasin"],
                            ["value" => "nettforum", "prefLabel" => "nettforum"],
                            ["value" => "some", "prefLabel" => "sosiale medier"],
                        ],
                        "search" => [
                            "placeholder" => "Velg fra menyen",
                        ],
                        "edit" => [
                            "allow_new_values" => false,
                            "placeholder" => "Medieformatet kritikken er publisert i",
                            "help" => "Hvilket medieformat er omtalen publisert i? Verdi velges fra <a target=\"_blank\" href=\"/norsk-litteraturkritikk/veiledning#medieformat\">databasens egen typologi for medieformat</a>.",
                        ],
                    ],

                    // Type
                    [
                        "key" => "kritikktype",
                        "type" => "select",
                        "multiple" => true,
                        "defaultValue" => [],
                        "values" => [
                            ["value" => "bokanmeldelse", "prefLabel" => "bokanmeldelse"],
                            ["value" => "teateranmeldelse", "prefLabel" => "teateranmeldelse"],
                            ["value" => "kronikk", "prefLabel" => "kronikk"],
                            ["value" => "debattinnlegg", "prefLabel" => "debattinnlegg"],
                            ["value" => "portrett", "prefLabel" => "portrett"],
                            ["value" => "essay", "prefLabel" => "essay"],
                            ["value" => "artikkel", "prefLabel" => "artikkel"],
                            ["value" => "pamflett", "prefLabel" => "pamflett"],
                            ["value" => "avhandling", "prefLabel" => "avhandling"],
                            ["value" => "litteraturhistorie", "prefLabel" => "litteraturhistorie"],
                            ["value" => "kåseri", "prefLabel" => "kåseri"],
                            ["value" => "samtale", "prefLabel" => "samtale"],
                            ["value" => "parodi", "prefLabel" => "parodi"],
                            ["value" => "lyrikk", "prefLabel" => "lyrikk"],
                            ["value" => "roman", "prefLabel" => "roman"],
                            ["value" => "drama", "prefLabel" => "drama"],
                            ["value" => "annet", "prefLabel" => "annet"],
                        ],
                        "search" => [
                            "type" => "array",
                            "placeholder" => "Velg fra menyen",
                        ],
                        "edit" => [
                            "allow_new_values" => false,
                            "placeholder" => "Velg aktuell kategori for kritikktype",
                            "help" => "Hvilken kritikktype er det snakk om? Én eller flere verdier velges fra <a target=\"_blank\" href=\"/norsk-litteraturkritikk/veiledning#kritikktype\">databasens egen typologi for kritikktype</a>. Begynn å skrive inn aktuell kritikktype, og velg riktig kategori fra listen som dukker opp.<hr>Det er mulig å kategorisere kritikken under flere typer, dersom det er aktuelt. Velg/skriv inn én kategori av gangen, slik at kategoriene vises som enkeltord med en grå boks rundt.",
                        ],
                    ],

                    // Emneord
                    [
                        "key" => "tags",
                        "type" => "select",
                        "multiple" => true,
                        "defaultValue" => [],

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "placeholder" => "F.eks. modernisme, politisk litteratur, ...",
                        ],
                        "edit" => [
                            "placeholder" => "Emneord fra Humord-vokabularet",
                            "help" => "Kan kritikken kategoriseres under relevante emneord? Dette feltet er integrert det kontrollerte emnevokabularet <a target=\"_blank\" href=\"https://app.uio.no/ub/emnesok/humord/search\">Humord</a>. Det er mulig å legge inn flere emneord, skriv da inn ett emneord av gangen, slik at hvert emne vises med en grå boks rundt.",
                            "remote_source" => [
                                "url" => "https://data.ub.uio.no/skosmos/rest/v1/search?labellang=nb&query={QUERY}*&type=skos:Concept&vocab=humord",
                            ],
                            "allow_new_values" => false,
                        ]
                    ],

                    // Dato
                    [
                        "key" => "dato",
                        "type" => "simple",

                        "columnClassName" => "dt-body-nowrap",

                        "search" => [
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
                            "placeholder" => "YYYY-MM-DD",
                            "help" => "Skriv inn publiseringsdatoen for omtalen, bruk formatet ÅR-MÅNED-DAG. Om kritikken er publisert i deler over flere datoer, skrives samtlige datoer inn i feltet, adskilt med semikolon og mellomrom. Eksempel: 1885-01-24; 1885-01-31; 1885-02-02",
                        ]
                    ],

                    // Språk
                    [
                        "key" => "spraak",
                        "type" => "select",
                        "multiple" => true,
                        "defaultValue" => [],

                        "search" => [
                            "advanced" => true,
                            "type" => "array",
                            "widget" => "autocomplete",
                        ],
                        "edit" => [
                            "preload" => true,
                            "placeholder" => "Språket kritikken er skrevet på",
                            "help" => "Hvilket språk er kritikken skrevet på? Bruk liten forbokstav. For norske tekster, skriv nynorsk eller bokmål. Begynn å skrive inn aktuelt språk og velg fra listen som dukker opp. Trykk på «opprett [...]» om det aktuelle språket ikke finnes i listen. Flere verdier kan registreres ved behov, skriv da inn et språk av gangen, slik at hvert språk vises med en grå boks rundt.",
                        ]
                    ],

                    // Tittel
                    [
                        "key" => "tittel",
                        "type" => "simple",

                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Tittel eller overskrift for kritikken",
                            "help" => "Hva er tittelen på kritikken? Ikke bruk anførselstegn, med mindre anførselstegn er en del av selve tittelen.<hr>Om tittel mangler, la feltet stå tomt."
                        ]

                    ],

                    [
                        "key" => "utgivelsessted",
                        "type" => "autocomplete",

                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Geografisk sted der kritikken ble publisert",
                            "help" => "Hvor ble kritikken publisert? Bruk samme skrivemåte som på tittelbladet. Ved mer enn ett utgivelsessted, bruk «&» som skilletegn mellom stedene. Eksempel: Christiania & København"
                        ]
                    ],
                    [
                        "key" => "aargang",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn årgang om det er aktuelt",
                            "help" => "Skriv inn årgang, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "bind",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn bind om det er aktuelt",
                            "help" => "Skriv inn bind, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "hefte",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn hefte om det er aktuelt",
                            "help" => "Skriv inn hefte, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "nummer",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn nummer om det er aktuelt",
                            "help" => "Skriv inn nummer, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "sidetall",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn sidetall om det er aktuelt",
                            "help" => "Hvilken eller hvilke sider er kritikken publisert på? Bruk arabiske tall, med unntak av sidetall som oppgis i romertall i selve teksten. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3) eller (220-244; 400-422).",
                        ],
                    ],
                    [
                        "key" => "kommentar",
                        "type" => "simple",

                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll eventuelt inn annen relevant informasjon",
                            "help" => "Dette feltet fungerer som et fotnotefelt hvor annen relevant informasjon legges inn, for eksempel anledningen for omtalen. Om det allerede ligger tematisk informasjon i feltet (sosialistisk, nynorsk, e.l.), skal dette oversettes til aktuelle Humord og flyttes til feltet for emneord.",
                        ],
                    ],
                    [
                        "key" => "utgivelseskommentar",
                        "type" => "simple",

                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Ikke bruk. Dette feltet skal fases ut.",
                        ]
                    ],
                    [
                        "key" => "fulltekst_url",
                        "type" => "url",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => [
                            "placeholder" => "Fyll inn dersom kritikken er tilgjengelig digitalt",
                            "help" => "Kopier og lim inn eventuell lenke her, eksempelvis til Nasjonalbibliotekets digitale arkiv, nettaviser, eller NRKs nettradio og nett-TV."
                        ]
                    ],

                ],
            ],

            // Omtale av
            [
                "key" => "omtale_av",
                "fields" => [

                    [
                        "key" => "subjectWorks",
                        "type" => "entities",

                        "showInTableView" => false,
                        "search" => false,

                        "entityType" => Work::class,
                        "modelAttribute" => "subjectWorks",

                        "relationship" => [
                            "table" => "litteraturkritikk_subject_work",
                            "source_id" => "record_id",
                            "target_id" => "work_id",
                        ],
                        "pivotFields" => [
                            [
                                "key" => "position",
                                "type" => "simple",
                                "edit" => false,
                            ],
                            [
                                "key" => "edition",
                                "type" => "simple",
                            ],
                        ],
                    ],
                    [
                        "key" => "forfatter",
                        "type" => "simple",

                        "edit" => false,

                        "search" => [
                            "widget" => "autocomplete",
                            "placeholder" => "Du kan trunkere med *, ",
                            "type" => "ts",
                            "ts_index" => "forfatter_ts",
                        ],
                    ],
                    [
                        "key" => "verk_tittel",
                        "type" => "simple",

                        "edit" => false,

                        "search" => [
                            "widget" => "autocomplete",
                            "placeholder" => "Tittel på omtalt verk",
                            "type" => "ts",
                            "ts_index" => "verk_tittel_ts",
                        ],
                    ],
                    // Språk
                    [
                        "key" => "verk_spraak",
                        "type" => "select",
                        "defaultValue" => [],
                        "multiple" => true,

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "advanced" => true,
                            "placeholder" => "Språket den omtalte utgaven er skrevet på",
                        ],
                        "edit" => false,
                    ],
                    // Språk
                    [
                        "key" => "verk_utgivelsessted",
                        "type" => "autocomplete",
                        "search" => [
                            "advanced" => true,
                        ],
                        "edit" => false,
                    ],
                    [
                        "key" => "verk_dato",
                        "type" => "simple",
                        "edit" => false,
                        "showInRecordView" => false,
                        "search" => [
                            "sort_index" => "verk_dato_s",
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
                    ],
                    [
                        "key" => "subjectPersons",
                        "type" => "entities",

                        "showInTableView" => false,
                        "search" => false,

                        "entityType" => Person::class,
                        "modelAttribute" => "subjectPersons",

                        "relationship" => [
                            "table" => "litteraturkritikk_subject_person",
                            "source_id" => "record_id",
                            "target_id" => "person_id",
                        ],
                        "pivotFields" => [
                            [
                                "key" => "position",
                                "type" => "simple",
                                "edit" => false,
                            ],
                        ],

                        // "pivotTable" => "litteraturkritikk_record_discusses",
                        // "pivotTableKey" => "discussed_id",
                    ],
                    // [
                    //     "key" => "omtalt_person",
                    //     "type" => "search_only",


                    //     "search" => [
                    //         "widget" => "autocomplete",
                    //         "placeholder" => "Du kan trunkere med *, ",
                    //         "type" => "ts",
                    //         "ts_index" => "omtalt_person_ts",
                    //     ],
                    // ],

                ]
            ],

            // Posten
            [
                "key" => "databaseposten",
                "fields" => [

                    // Opprettet
                    [
                        "key" => "created_at",
                        "type" => "simple",
                        "edit" => false,
                        "search" => false,

                        "columnClassName" => "dt-body-nowrap",
                    ],

                    // Sist endret
                    [
                        "key" => "updated_at",
                        "type" => "simple",
                        "edit" => false,
                        "search" => false,

                        "columnClassName" => "dt-body-nowrap",
                    ],

                    // Korrekturstatus
                    [
                        "key" => "korrekturstatus",
                        "type" => "select",
                        "datatype" => self::DATATYPE_INT,
                        "values" => [
                            ["value" => 1, "prefLabel" => "Ikke korrekturlest"],
                            ["value" => 2, "prefLabel" => "Må korrekturleses mot fysisk materiale"],
                            ["value" => 3, "prefLabel" => "Korrekturlest mot fysisk materiale"],
                            ["value" => 4, "prefLabel" => "Korrekturlest mot og lenket til digitalt materiale"],
                        ],
                        "search" => [
                            "placeholder" => "Velg fra menyen",
                        ],
                        "columnClassName" => "dt-body-nowrap",
                        "edit" => [
                            "placeholder" => "Oppdater alltid postens korrekturstatus etter endt arbeid",
                            "help" => "Det er viktig å alltid oppdatere korrekturstatus etter endt arbeid med posten. Marker om posten er ferdig korrekturlest, og mot hvilken type materiale. Om postens verk og/eller kritikk ikke er tilgjengelig digitalt, marker at posten må korrekturleses mot fysisk materiale, dersom du selv ikke har tilgang til det fysiske materiale for øyeblikket. Se <a href=\"/norsk-litteraturkritikk/veiledning\">redigeringsveiledning</a> for nærmere informasjon.",
                        ],

                    ],

                    // Slettet
                    [
                        "key" => "deleted_at",
                        "type" => "simple",
                        "edit" => false,
                        "search" => false,

                        "columnClassName" => "dt-body-nowrap",
                    ],
                ],
            ],
        ],
    ];
}
