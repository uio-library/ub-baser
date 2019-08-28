<?php

namespace App\Schema;

class PersonsField extends SchemaField
{
    public const TYPE = 'persons';

    public function setModelAttribute(string $value)
    {
        $this->data['modelAttribute'] = $value;
    }

    public function setPersonRole(string $value)
    {
        $this->data['personRole'] = $value;
    }
}
