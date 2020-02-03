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
        'placeholder' => '',
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'enabled' => 'boolean',
        'help' => 'string',
        'placeholder' => 'string',
    ];
}
