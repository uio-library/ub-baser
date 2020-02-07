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
        'label' => null,  // For boolean input fields
        'cssClass' => null,
        'placeholder' => '',
        'allow_new_values' => true,  // For array input fields
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'enabled' => 'boolean',
        'help' => 'string',
        'label' => 'string',
        'cssClass' => 'string',
        'placeholder' => 'string',
        'allow_new_values' => 'boolean',
    ];
}
