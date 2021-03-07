<?php

namespace App\Schema;

class EntityField extends SchemaField
{
    public const TYPE = 'entity';

    public function setModelAttribute(string $value)
    {
        $this->data['modelAttribute'] = $value;
    }

    public function setEntityType(string $value)
    {
        $this->data['entityType'] = $value;
    }

    public function setEntitySchema(string $value)
    {
        if (is_null($this->parent)) {
            $this->data['entitySchema'] = new $value($this);
        }
    }
}
