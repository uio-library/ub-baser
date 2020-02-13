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
                        "type" => "tags",
                        "defaultValue" => [],

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "advanced" => true,
                            "placeholder" => "Språket den omtalte utgaven er skrevet på",
                        ],
                        "edit" => [
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
                        "type" => "tags",
                        "defaultValue" => [],

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "advanced" => true,
                            "placeholder" => "Språket originalutgaven er skrevet på",
                        ],
                        "edit" => [
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
                                "type" => "tags",
                                "defaultValue" => ["forfatter"],
                                "values" => [
                                    ["id" => "forfatter", "prefLabel" => "forfatter"],
                                    ["id" => "redaktør", "prefLabel" => "redaktør"],
                                    ["id" => "gjendikter", "prefLabel" => "gjendikter"],
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
                        "type" => "enum",
                        "values" => [
                            ["id" => "lyrikk", "label" => "lyrikk"],
                            ["id" => "drama", "label" => "drama"],
                            ["id" => "fortelling", "label" => "fortelling"],
                            ["id" => "roman", "label" => "roman"],
                            ["id" => "kortprosa", "label" => "kortprosa"],
                            ["id" => "essay", "label" => "essay"],
                            ["id" => "tegneserie", "label" => "tegneserie"],
                            ["id" => "annen skjønnlitteratur", "label" => "annen skjønnlitteratur"],
                            ["id" => "biografi", "label" => "biografi"],
                            ["id" => "taler", "label" => "taler"],
                            ["id" => "brev", "label" => "brev"],
                            ["id" => "avhandling", "label" => "avhandling"],
                            ["id" => "litteraturhistorie", "label" => "litteraturhistorie"],
                            ["id" => "lærebok", "label" => "lærebok"],
                            ["id" => "annen sakprosa", "label" => "annen sakprosa"],
                        ],

                        "search" => [
                            "placeholder" => "Sjanger til det omtalte verket",
                        ],
                        "edit" => [
                            "allow_new_values" => true,
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
                                "type" => "tags",
                                "defaultValue" => ["kritiker"],
                                "values" => [
                                    ["id" => "kritiker", "prefLabel" => "kritiker"],
                                    ["id" => "redaktør", "prefLabel" => "redaktør"],
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
                        "type" => "enum",
                        "columnClassName" => "dt-body-nowrap",
                        "values" => [
                            ["id" => "avis", "label" => "Avis"],
                            ["id" => "tidsskrift", "label" => "Tidsskrift"],
                            ["id" => "bok", "label" => "Bok"],
                            ["id" => "radio", "label" => "Radio"],
                            ["id" => "tv", "label" => "TV"],
                            ["id" => "video", "label" => "Video"],
                            ["id" => "blogg", "label" => "Blogg"],
                            ["id" => "podcast", "label" => "Podcast"],
                            ["id" => "nettmagasin", "label" => "Nettmagasin"],
                            ["id" => "nettforum", "label" => "Nettforum"],
                            ["id" => "some", "label" => "Sosiale medier"],
                        ],
                        "search" => [
                            "operators" => ["ex"],
                        ],
                        "edit" => [
                            "placeholder" => "Medieformatet kritikken er publisert i",
                            "help" => "Hvilket medieformat er omtalen publisert i? Verdi velges fra <a target=\"_blank\" href=\"/norsk-litteraturkritikk/veiledning#medieformat\">databasens egen typologi for medieformat</a>.",
                        ],
                    ],

                    // Type
                    [
                        "key" => "kritikktype",
                        "type" => "tags",
                        "defaultValue" => [],

                        "search" => [
                            "type" => "array",
                            "widget" => "autocomplete",
                            "placeholder" => "",
                        ],
                        "edit" => [
                            "allow_new_values" => false,
                            "preload" => true,
                            "placeholder" => "Velg aktuell kategori for kritikktype",
                            "help" => "Hvilken kritikktype er det snakk om? Én eller flere verdier velges fra <a target=\"_blank\" href=\"/norsk-litteraturkritikk/veiledning#kritikktype\">databasens egen typologi for kritikktype</a>. Begynn å skrive inn aktuell kritikktype, og velg riktig kategori fra listen som dukker opp.<hr>Det er mulig å kategorisere kritikken under flere typer, dersom det er aktuelt. Velg/skriv inn én kategori av gangen, slik at kategoriene vises som enkeltord med en grå boks rundt.",
                        ],
                    ],

                    // Emneord
                    [
                        "key" => "tags",
                        "type" => "tags",
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
                        "type" => "tags",
                        "defaultValue" => [],

                        "search" => [
                            "advanced" => true,
                            "type" => "array",
                            "widget" => "autocomplete",
                        ],
                        "edit" => [
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
                        "type" => "enum",
                        "datatype" => self::DATATYPE_INT,
                        "values" => [
                            ["id" => 1, "label" => "Ikke korrekturlest"],
                            ["id" => 2, "label" => "Må korrekturleses mot fysisk materiale"],
                            ["id" => 3, "label" => "Korrekturlest mot fysisk materiale"],
                            ["id" => 4, "label" => "Korrekturlest mot og lenket til digitalt materiale"],
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
