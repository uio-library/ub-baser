<?php

namespace App\Schema;

class BooleanField extends SchemaField
{
    public const TYPE = 'boolean';

    public function __construct(string $key, string $schemaPrefix, array $schemaOptions)
    {
        parent::__construct($key, $schemaPrefix, $schemaOptions);

        // Defaults
        $this->data['defaultValue'] = false;

        $this->data['datatype'] = Schema::DATATYPE_BOOL;
    }
}
