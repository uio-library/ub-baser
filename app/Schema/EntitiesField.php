<?php

namespace App\Schema;

/**
 * @property Relationship $relationship
 * @property array $entityType
 * @property string $modelAttribute
 * @property SchemaField[] $pivotFields
 */
class EntitiesField extends SchemaField
{
    public const TYPE = 'entities';

    /**
     * Set default value.
     */
    public function setDefaults()
    {
        $this->data['pivotFields'] = [];
        $this->data['defaultValue'] = [];

        // Pivot table
        // $this->data['pivotTable'] = null;
        // $this->data['pivotTableKey'] = null;
        // $this->data['relatedPivotKey'] = 'record_id';
    }

    public function setModelAttribute(string $value)
    {
        $this->data['modelAttribute'] = $value;
    }

    public function setEntityType(string $value)
    {
        $this->data['entityType'] = [
            'className' => $value,
            'name' => $value::$shortName,
            'schema' => new $value::$schema(),
        ];
    }

    public function setRelationship(array $value)
    {
        $this->data['relationship'] = new Relationship();
        $this->data['relationship']->init($value);
    }

    /*
    public function setPivotTableKey(string $value)
    {
        $this->data['pivotTableKey'] = $value;
    }

    public function setRelatedPivotKey(string $value)
    {
        $this->data['relatedPivotKey'] = $value;
    }
    */

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
