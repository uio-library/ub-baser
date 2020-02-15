<?php

namespace App\Schema;

class BooleanField extends SchemaField
{
    public const TYPE = 'boolean';

    public function setDefaults()
    {
        // Defaults
        $this->data['defaultValue'] = false;
        $this->data['datatype'] = Schema::DATATYPE_BOOL;
    }
}
