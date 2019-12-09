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

    public $prefix;
    public $view;
    public $primaryId = 'id';
    public $costLimit = 0;

    protected $schema;
    protected $schemaOptions = [
        'defaultOperators' => [
            'eq',
            'neq',
            'isnull',
            'notnull',
        ],
    ];

    public $fields = [];
    public $groups = [];

    public function __construct()
    {
        $this->fields = SchemaFields::make($this->schema['fields'], $this->prefix, $this->schemaOptions);

        foreach (Arr::get($this->schema, 'groups', []) as $group) {
            $this->groups[] = SchemaGroup::make($group, $this->prefix, $this->schemaOptions);
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
     * @return SchemaField[]
     */
    public function flat(): array
    {
        $out = [];
        foreach ($this->fields as &$field) {
            $out[] = $field;
        }
        foreach ($this->groups as &$group) {
            foreach ($group->fields as &$field) {
                $out[] = $field;
            }
        }

        return $out;
    }

    /**
     * Return an array of SchemaField indexed by the field keys.
     *
     * @return SchemaField[]
     */
    public function keyed(): array
    {
        $fields = $this->flat();
        $out = [];
        foreach ($fields as &$field) {
            $out[$field->key] = $field;
        }

        return $out;
    }

    /**
     * @return string[]
     */
    public function keys(): array
    {
        return array_map(function ($field) {
            return $field->key;
        }, $this->flat());
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
