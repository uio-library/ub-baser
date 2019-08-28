<?php

namespace App\Schema;

use Illuminate\Support\Arr;

class SimpleField extends SchemaField
{
    public const TYPE = 'simple';

    public function setSearchOptions(array $value, array $schemaOptions): void
    {
        if (Arr::get($value, 'type') == 'rangeslider' && !Arr::has($value, 'minValue')) {
            $value['minValue'] = $schemaOptions['minYear'];
            $value['maxValue'] = $schemaOptions['maxYear'];
        }

        parent::setSearchOptions($value, $schemaOptions);
    }
}
