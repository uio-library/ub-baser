<?php

namespace App\Schema;

class IncrementingField extends SchemaField
{
    public const TYPE = 'incrementing';

    public function __construct()
    {
        parent::__construct();

        // Defaults
        $this->data['displayable'] = false;
        $this->data['searchable'] = static::SEARCH_DISABLED;
        $this->data['editable'] = false;
    }
}
