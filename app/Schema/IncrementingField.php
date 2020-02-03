<?php

namespace App\Schema;

class IncrementingField extends SchemaField
{
    public const TYPE = 'incrementing';

    public function __construct(string $key, array $schemaOptions)
    {
        parent::__construct($key, $schemaOptions);

        // Defaults
        $this->data['displayable'] = false;
        $this->data['search']->init(false);
        $this->data['edit']->init(false);
    }
}
