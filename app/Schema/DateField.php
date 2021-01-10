<?php

namespace App\Schema;

use App\Base;

class DateField extends SchemaField
{
    public const TYPE = 'date';

    public $operators = [
        Operators::IN_RANGE,
        Operators::OUTSIDE_RANGE,
        Operators::IS_NULL,
        Operators::NOT_NULL,
    ];

    public function setDefaults()
    {
        // Defaults
        $this->data['datatype'] = Schema::DATATYPE_DATE;
    }
}
