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
    'letras' => [
        'columns' => [
            [
                'field' => 'forfatter',
                'type'  => 'text',
            ],[
                'field' => 'land',
                'type'  => 'text',
            ],[
                'field' => 'tittel',
                'type'  => 'text',
            ],[
                'field' => 'utgivelsesaar',
                'type'  => 'text',
            ],[
                'field' => 'sjanger',
                'type'  => 'text',
            ],[
                'field' => 'oversetter',
                'type'  => 'text',
            ],[
                'field' => 'tittel2',
                'type'  => 'text',
            ],[
                'field' => 'utgivelsessted',
                'type'  => 'text',
            ],[
                'field' => 'utgivelsesaar2',
                'type'  => 'text',
            ],[
                'field' => 'forlag',
                'type'  => 'text',
            ],[
                'field' => 'foretterord',
                'type'  => 'text',
            ],[
                'field' => 'spraak',
                'type'  => 'text',
            ]
        ],
        'default' => [
            'column' => 'utgivelsesaar',
            'order' => 'desc'
        ],
        'cols' => [
            [
                'field' => 'forfatter',
                'type'  => 'text',
            ],[
                'field' => 'tittel',
                'type'  => 'text',
            ],[
                'field' => 'sjanger',
                'type'  => 'text',
            ],[
                'field' => 'utgivelsesaar',
                'type'  => 'text',
            ]
        ],
        'default2' => [
            'column' => 'utgivelsesaar',
            'order' => 'desc'
        ],
    ],
    'opes' => [
        'columns' => [
            [
                'field' => 'inv_no',
                'type'  => 'text',
            ],
            [
                'field' => 'type_of_text_file',
                'width' => '40%',
                'type'  => 'exact',
            ],
            
        ],
        'default' => [
            'column' => 'inv_no',
            'order' => 'desc'
        ],
    ],
];
