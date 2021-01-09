<?php

namespace App\Schema;

class EntitiesField extends SchemaField
{
    public const TYPE = 'entities';

    /**
     * Set default value.
     */
    public function setDefaults()
    {
        $this->data['relatedPivotKey'] = 'record_id';
    }

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
        $this->data['entitySchema'] = new $value();
    }

    public function setPivotTable(string $value)
    {
        $this->data['pivotTable'] = $value;
    }

    public function setPivotTableKey(string $value)
    {
        $this->data['pivotTableKey'] = $value;
    }

    public function setRelatedPivotKey(string $value)
    {
        $this->data['relatedPivotKey'] = $value;
    }

    public function setPivotFields(array $values)
    {
        $values = array_map(
            function ($value) {
                $value['key'] = $this->key . ':' . $value['key'];
                return self::make($value, $this->schemaPrefix, $this);
            },
            $values
        );
        $this->data['pivotFields'] = $values;
    }
}
