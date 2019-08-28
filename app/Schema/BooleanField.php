<?php

namespace App\Schema;

class BooleanField extends SchemaField
{
    public const TYPE = 'boolean';

    public function __construct()
    {
        parent::__construct();

        // Defaults
        $this->data['defaultValue'] = false;
    }

    public function setHelp($value)
    {
        $this->data['help'] = $value;
    }
}
