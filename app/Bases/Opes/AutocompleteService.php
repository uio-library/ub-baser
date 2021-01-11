<?php

namespace App\Bases\Opes;

class AutocompleteService extends \App\Bases\AutocompleteService
{
    /**
     * The completer method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no completer was found.
     *
     * @var string
     */
    protected $defaultCompleter = 'simpleStringCompleter';

    /**
     * The lister method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no lister was found.
     *
     * @var string
     */
    protected $defaultLister = 'simpleLister';

    /**
     * Completer methods to use with each field.
     *
     * @var array
     */
    protected $completers = [
        'people' => 'jsonArrayCompleter',
        'places' => 'jsonArrayCompleter',
        'subjects' => 'jsonArrayCompleter',
    ];

    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [
        'people' => 'jsonArrayLister',
        'places' => 'jsonArrayLister',
        'subjects' => 'jsonArrayLister',
    ];
}
