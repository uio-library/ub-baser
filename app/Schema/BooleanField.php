<?php

namespace App\Schema;

class BooleanField extends SchemaField
{
    public const TYPE = 'boolean';

    public function __construct(string $key, array $schemaOptions)
    {
        parent::__construct($key, $schemaOptions);

        // Defaults
        $this->data['defaultValue'] = false;

        $this->data['datatype'] = Schema::DATATYPE_BOOL;
    }
}
