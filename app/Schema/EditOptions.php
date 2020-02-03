<?php

namespace App\Schema;

class EditOptions extends FieldOptions
{
    /**
     * Default data
     */
    public $data = [
        'enabled' => true,
        'help' => '',
        'cssClass' => null,
        'placeholder' => '',
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'enabled' => 'boolean',
        'help' => 'string',
        'cssClass' => 'string',
        'placeholder' => 'string',
    ];
}
