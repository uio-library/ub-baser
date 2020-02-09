<?php

namespace App\Schema;

class IncrementingField extends SchemaField
{
    public const TYPE = 'incrementing';

    public function __construct(string $key, string $schemaPrefix, array $schemaOptions)
    {
        parent::__construct($key, $schemaPrefix, $schemaOptions);

        // Defaults
        $this->data['displayable'] = false;
        $this->data['search']->init(false);
        $this->data['edit']->init(false);
    }
}
