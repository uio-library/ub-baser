<?php

return [


    'dommer' => [

        'columns' => [
            [
                'field' => 'navn',
                'type'  => 'text',
            ],
            [
                'field' => 'kilde_navn',
                'width' => '40%',
                'type'  => 'exact',
            ],
            [
                'field' => 'aar',
                'width' => '10%',
                'type'  => 'exact',
            ],
            [
                'field' => 'side',
                'width' => '10%',
                'type'  => 'exact',
            ],
        ],

        'default' => [
            'column' => 'aar',
            'order' => 'desc'
        ],

    ],

    'beyer' => [

        'columns' => [
            [
                'field' => 'kritikktype',
                'type'  => 'array',
            ],[
                'field' => 'kommentar',
                'type'  => 'text',
            ],[
                'field' => 'spraak',
                'type'  => 'text',
            ],[
                'field' => 'tittel',
                'type'  => 'text',
            ],[
                'field' => 'publikasjon',
                'type'  => 'text',
            ],[
                'field' => 'utgivelsessted',
                'type'  => 'text',
            ],[
                'field' => 'aar',
                'type'  => 'text',
            ],[
                'field' => 'dato',
                'type'  => 'text',
            ],[
                'field' => 'aargang',
                'type'  => 'text',
            ],[
                'field' => 'nummer',
                'type'  => 'text',
            ],[
                'field' => 'bind',
                'type'  => 'text',
            ],[
                'field' => 'hefte',
                'type'  => 'text',
            ],[
                'field' => 'sidetall',
                'type'  => 'text',
            ],[
                'field' => 'utgivelseskommentar',
                'type'  => 'text',
            ],[
                'field' => 'kritiker_etternavn',
                'type'  => 'text',
            ],[
                'field' => 'kritiker_fornavn',
                'type'  => 'text',
            ],[
                'field' => 'kritiker_kjonn',
                'type'  => 'text',
            ],[
                'field' => 'kritiker_pseudonym',
                'type'  => 'text',
            ],[
                'field' => 'kritiker_kommentar',
                'type'  => 'text',
            ],[
                'field' => 'forfatter_etternavn',
                'type'  => 'text',
            ],[
                'field' => 'forfatter_fornavn',
                'type'  => 'text',
            ],[
                'field' => 'forfatter_kjonn',
                'type'  => 'text',
            ],[
                'field' => 'forfatter_kommentar',
                'type'  => 'text',
            ],[
                'field' => 'verk_tittel',
                'type'  => 'text',
            ],[
                'field' => 'verk_aar',
                'type'  => 'text',
            ],[
                'field' => 'verk_sjanger',
                'type'  => 'text',
            ],[
                'field' => 'verk_kommentar',
                'type'  => 'text',
            ],[
                'field' => 'verk_utgivelsessted',
                'type'  => 'text',
            ]
        ],

    ],

];