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

];
