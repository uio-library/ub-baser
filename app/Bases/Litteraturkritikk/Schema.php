<?php

namespace App\Bases\Litteraturkritikk;

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
                "type" => "simple",

                "displayable" => false,
                "edit" => false,

                "search" => [
                    "placeholder" => "Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.",
                    "type" => "ts",
                    "index" => "any_field_ts",
                    "operators" => ["eq", "neq"],
                ],
            ],

            // Person-søk (forfatter eller kritiker)
            [
                "key" => "persons",
                "type" => "autocomplete",

                "displayable" => false,
                "edit" => false,

                "search" => [
                    "placeholder" => "Fornavn og/eller etternavn",
                    "type" => "ts",
                    "index" => "person_ts",
                    "operators" => ["eq", "neq"],
                ],
            ],
        ],

        "groups" => [

            // Verket
            [
                "label" => "Omtale av",
                "fields" => [

                    // Tittel
                    [
                        "key" => "verk_tittel",
                        "type" => "autocomplete",

                        "search" => [
                            "placeholder" => "Tittel på omtalt verk",
                            "type" => "ts",
                            "index" => "verk_tittel_ts",
                        ],

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

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "advanced" => true,
                            "placeholder" => "Språket den omtalte utgaven er skrevet på",
                        ],
                        "edit" => [
                            "preload" => true,
                            "allow_new_values" => true,
                            "placeholder" => "Språket den omtalte utgaven er skrevet på",
                            "help" => "Skriv inn språket den omtalte utgaven er skrevet på. Bruk liten forbokstav. For norsk, skriv bokmål eller nynorsk. Begynn å skrive inn aktuelt språk og velg fra listen som dukker opp. Trykk på «opprett [...]» om det aktuelle språket ikke finnes i listen. Flere verdier kan registreres ved behov, skriv da inn et språk av gangen, slik at hvert språk vises med en grå boks rundt.",
                        ],
                    ],

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

                    // Forfatter
                    [
                        "key" => "verk_forfatter",
                        "type" => "entities",
                        "entityType" => Person::class,
                        "entitySchema" => PersonSchema::class,
                        "modelAttribute" => "forfattere",
                        "pivotTable" => "litteraturkritikk_record_person",
                        "pivotTableKey" => "person_id",
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
                                "key" => "posisjon",
                                "type" => "simple",
                                "edit" => false,
                            ],
                        ],

                        "search" => [
                            "widget" => "autocomplete",
                            "placeholder" => "Fornavn og/eller etternavn",
                            "type" => "ts",
                            "index" => "forfatter_ts",
                            "operators" => [
                                "eq",
                                "neq",
                                "isnull",
                                "notnull",
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

                        "displayable" => false,
                        "search" => false,

                        // "default" => false,
                        "edit" => [
                            "label" => "Flere forfattere",
                            "help" => "Kryss av her dersom det er flere forfattere enn det er hensiktsmessig å registrere. Forsøk alltid å få med forfatterne som er hovedfokus for omtalen.",
                            "cssClass" => "col-md-12",
                        ],
                    ],

                    // Utgivelsessted
                    [
                        "key" => "verk_utgivelsessted",
                        "type" => "autocomplete",

                        "search" => [
                            "advanced" => true,
                        ],

                        "edit" => [
                            "placeholder" => "Geografisk utgivelsessted for omtalt utgave",
                            "help" => "Skriv inn utgivelsessted, bruk samme skrivemåte som på tittelbladet. Ved mer enn ett utgivelsessted, bruk «&» som skilletegn mellom stedene. Eksempel: Christiania & København",
                        ]
                    ],

                    // År
                    [
                        "key" => "verk_dato",
                        "type" => "simple",

                        "columnClassName" => "dt-body-nowrap",

                        "search" => [
                            "advanced" => true,
                            "type" => "range",
                            "widget" => "rangeslider",
                            "widgetOptions" => [
                                "minValue" => 1789,
                            ],
                        ],
                        "edit" => [
                            "placeholder" => "Utgivelsesår for omtalt utgave",
                            "help" => "Fyll inn utgivelsesår for den omtalte utgaven av verket.",
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
            ],

            // Kritikken
            [
                "label" => "Kritikken",
                "fields" => [

                    // Kritiker
                    [
                        "key" => "kritiker",
                        "type" => "entities",
                        "entityType" => Person::class,
                        "entitySchema" => PersonSchema::class,
                        "modelAttribute" => "kritikere",
                        "pivotTable" => "litteraturkritikk_record_person",
                        "pivotTableKey" => "person_id",
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
                                "key" => "posisjon",
                                "type" => "simple",
                                "edit" => false,
                            ],
                        ],

                        "search" => [
                            "widget" => "autocomplete",
                            "placeholder" => "Fornavn og/eller etternavn",
                            "type" => "ts",
                            "index" => "kritiker_ts",
                        ],
                        "edit" => [
                            "cssClass" => "col-md-12",
                            "help" => "Trykk på «Legg til» og begynn å skrive kritikerens navn i feltet. Om vedkommende finnes i personregisteret, velg personens navn i listen som dukker opp i feltet. Om kritikeren ikke ligger i registeret, trykk «Opprett ny» for å opprette personen."
                        ],
                    ],

                    // mfl.
                    [
                        "key" => "kritiker_mfl",
                        "type" => "boolean",
                        // "default" => false,
                        "displayable" => false,
                        "search" => false,
                        "edit" => [
                            "label" => "Flere kritikere",
                            "help" => "Kryss av her dersom kritikken er signert av flere kritikere enn det er hensiktsmessig å registrere.",
                            "cssClass" => "col-md-12",
                        ],
                    ],

                    // Publikasjon
                    [
                        "key" => "publikasjon",
                        "type" => "autocomplete",

                        "search" => [
                            "placeholder" => "Publikasjon",
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
                            ["value" => "avis", "prefLabel" => "Avis"],
                            ["value" => "tidsskrift", "prefLabel" => "Tidsskrift"],
                            ["value" => "bok", "prefLabel" => "Bok"],
                            ["value" => "radio", "prefLabel" => "Radio"],
                            ["value" => "tv", "prefLabel" => "TV"],
                            ["value" => "video", "prefLabel" => "Video"],
                            ["value" => "blogg", "prefLabel" => "Blogg"],
                            ["value" => "podcast", "prefLabel" => "Podcast"],
                            ["value" => "nettmagasin", "prefLabel" => "Nettmagasin"],
                            ["value" => "nettforum", "prefLabel" => "Nettforum"],
                            ["value" => "some", "prefLabel" => "Sosiale medier"],
                        ],
                        "search" => [
                            "operators" => ["ex"],
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
                            ["value" => "forfatterportrett", "prefLabel" => "forfatterportrett"],
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
                            "placeholder" => "F.eks. teaterkritikk, forfatterportrett, ...",
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
                        "edit" => [
                            "placeholder" => "Fyll inn årgang om det er aktuelt",
                            "help" => "Skriv inn årgang, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "bind",
                        "type" => "simple",
                        "edit" => [
                            "placeholder" => "Fyll inn bind om det er aktuelt",
                            "help" => "Skriv inn bind, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "hefte",
                        "type" => "simple",
                        "edit" => [
                            "placeholder" => "Fyll inn hefte om det er aktuelt",
                            "help" => "Skriv inn hefte, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "nummer",
                        "type" => "simple",
                        "edit" => [
                            "placeholder" => "Fyll inn nummer om det er aktuelt",
                            "help" => "Skriv inn nummer, dersom det er aktuelt. Bruk arabiske tall. Skriv inn tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).",
                        ],
                    ],
                    [
                        "key" => "sidetall",
                        "type" => "simple",
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

            // Posten
            [
                "label" => "Databaseposten",

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
                            "operators" => ["ex"],
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
