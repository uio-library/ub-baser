<?php

return [
    'id' => 'ID',
    'basepath' => 'Path',
    'languages' => 'Language',
    'default_language' => 'Default language',
    'namespace' => 'Namespace',

    'error' => [
        'notfound' => 'No base «:name» was found',
        'recordnotfound' => 'Record not found',
        'recordtrashed' => 'The record is deprecated.  Log in to view it.',
    ],
    'notification' => [
        'recordtrashed' => 'Record deprecated. It will not be visible in search results and not for non-logged in users.',
        'recordrecovered' => 'Record restored.',
        'recordupdated' => 'Record updated.',
        'recordcreated' => 'Record created.',
    ],
    'status' => [
        'recordtrashed' => 'This record is deprecated. It\'s not displayed to non-logged in users, and does not show up in search results.',
        'unpublished' => 'This record is unpublished.',
        'unpublished_loggedin' => 'This record is unpublished. It\'s not shown unless you\'re logged in.',
    ],
];
