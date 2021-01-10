<?php

namespace App\Schema;

use Illuminate\Support\Arr;

abstract class Schema implements \JsonSerializable
{
    const UPPER_CASE = 'UPPER_CASE';
    const LOWER_CASE = 'LOWER_CASE';

    const DATATYPE_STRING = 'string';
    const DATATYPE_INT = 'int';
    const DATATYPE_BOOL = 'bool';
    const DATATYPE_DATE = 'date';

    public $primaryId = 'id';

    protected $schema;

    public $fields = [];
    public $groups = [];

    public function __construct()
    {
        $this->fields = SchemaFields::make($this->schema['fields'], $this->schema['id']);

        foreach (Arr::get($this->schema, 'groups', []) as $group) {
            $this->groups[] = SchemaGroup::make($group, $this->schema['id']);
        }

        // Check that schema keys are unique
        $keys = [];
        foreach ($this->keys() as $key) {
            if (in_array($key, $keys)) {
                throw new \RuntimeException('Schema key "' . $key . '" is not unique!');
            }
            $keys[] = $key;
        }
    }

    /**
     * @param bool $withPivotFields
     * @return SchemaField[]
     */
    public function flat($withPivotFields = false): array
    {
        $out = [];
        foreach ($this->fields as &$field) {
            $out[] = $field;
            if ($withPivotFields && isset($field->data['pivotFields'])) {
                foreach ($field->pivotFields as $pivotField) {
                    $out[] = $pivotField;
                }
            }
        }
        foreach ($this->groups as &$group) {
            foreach ($group->fields as &$field) {
                $out[] = $field;
                if ($withPivotFields && isset($field->data['pivotFields'])) {
                    foreach ($field->pivotFields as $pivotField) {
                        $out[] = $pivotField;
                    }
                }
            }
        }

        return $out;
    }

    /**
     * Return an array of SchemaField indexed by the field keys.
     *
     * @param bool $withPivotFields
     * @return SchemaField[]
     */
    public function keyed($withPivotFields = false): array
    {
        $fields = $this->flat($withPivotFields);
        $out = [];
        foreach ($fields as &$field) {
            $out[$field->key] = $field;
        }

        return $out;
    }

    /**
     * @param bool $withPivotFields
     * @return string[]
     */
    public function keys($withPivotFields = false): array
    {
        return array_map(function ($field) {
            return $field->key;
        }, $this->flat($withPivotFields));
    }

    public function jsonSerialize()
    {
        return [
            'primaryId' => $this->primaryId,
            'fields' => $this->fields,
            'groups' => $this->groups,
        ];
    }

    public function get(): array
    {
        return $this->jsonSerialize();
    }

    public function __isset($key)
    {
        return isset($this->keyed()[$key]);
    }

    public function __get($key)
    {
        return $this->keyed()[$key];
    }
}
