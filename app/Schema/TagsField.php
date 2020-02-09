<?php

namespace App\Schema;

class TagsField extends SchemaField
{
    public const TYPE = 'tags';

    /**
     * Set allowed values (optional)
     *
     * @param array $values
     */
    public function setValues($values): void
    {
        $this->data['values'] = $values;
    }
}
