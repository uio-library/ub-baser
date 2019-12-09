<?php

return [

   /*
    |--------------------------------------------------------------------------
    | Content Security Policy Middleware configuration
    |--------------------------------------------------------------------------
    */

    'content' => [
        'default' => 'local',
        'profiles' => [
            'local' => [
                'base-uri' => ["'self'"],
                'font-src' => [
                    "'self'",
                ],
                'img-src' => [
                    "'self'",
                ],
                'script-src' => [
                    "'self'",
                    "'unsafe-inline'",
                ],
                'style-src' => [
                    "'self'",
                    "'unsafe-inline'",
                ],
            ],
        ],
    ],
];
