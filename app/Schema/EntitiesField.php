<?php

namespace App\Schema;

class EntitiesField extends SchemaField
{
    public const TYPE = 'entities';
    public const ONE_TO_MANY_RELATION = 'one_to_many';
    public const MANY_TO_MANY_RELATION = 'many_to_many';

    /**
     * Set default value.
     */
    public function setDefaults()
    {
        $this->data['relatedPivotKey'] = 'record_id';
        $this->data['entityRelation'] = self::MANY_TO_MANY_RELATION;
    }

    public function setModelAttribute(string $value)
    {
        $this->data['modelAttribute'] = $value;
    }

    public function setEntityType(string $value)
    {
        $this->data['entityType'] = $value;
    }

    public function setEntityRelation(string $value)
    {
        if (!in_array($value, [self::ONE_TO_MANY_RELATION, self::MANY_TO_MANY_RELATION])) {
            throw new \ValueError('Invalid entity relation: ' . $value);
        }
        $this->data['entityRelation'] = $value;
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
