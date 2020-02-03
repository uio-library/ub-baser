<?php

namespace App\Schema;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class SchemaField implements JsonSerializable
{
    const TYPE = null;

    public $data = [];

    public static $types = [
        'autocomplete' => AutocompleteField::class,
        'boolean' => BooleanField::class,
        'incrementing' => IncrementingField::class,
        'persons' => PersonsField::class,
        'select' => SelectField::class,
        'enum' => EnumField::class,
        'simple' => SimpleField::class,
        'tags' => TagsField::class,
        'url' => UrlField::class,
    ];

    public function __construct(string $key, array $schemaOptions)
    {
        $this->data['key'] = $key;
        $this->data['type'] = static::TYPE;

        // Defaults
        $this->data['displayable'] = true;
        $this->data['editable'] = true;
        $this->data['orderable'] = true;
        $this->data['defaultValue'] = null;
        $this->data['datatype'] = Schema::DATATYPE_STRING;
        $this->data['search'] = new SearchConfig($key, static::TYPE, $schemaOptions);
        $this->data['help'] = null;
    }

    /**
     * Factory method to initialize a schema field from JSON data.
     *
     * @param array  $data
     * @param string $schemaPrefix
     * @param array  $schemaOptions
     *
     * @return SchemaField
     */
    public static function make(array $data, string $schemaPrefix, array $schemaOptions): self
    {
        $field = static::newFieldFromType(
            $data['type'],
            $schemaPrefix,
            $data['key'],
            $schemaOptions
        );

        foreach ($data as $key => $value) {
            if (in_array($key, ['type', 'key'])) {
                // pass
            } elseif (method_exists($field, 'set' . Str::ucfirst($key))) {
                $field->{'set' . Str::ucfirst($key)}($value, $schemaOptions);
            } else {
                throw new \RuntimeException('Unknown schema attribute: ' . $key);
            }
        }

        return $field;
    }

    /**
     * Factory method for a schema field of a given type.
     *
     * @param string $fieldType
     * @param string $schemaPrefix
     * @param string $key
     * @param array $schemaOptions
     *
     * @return mixed
     */
    public static function newFieldFromType(
        string $fieldType,
        string $schemaPrefix,
        string $key,
        array $schemaOptions
    ): self
    {
        if (!isset(static::$types[$fieldType])) {
            throw new \RuntimeException('Schema contains field of unrecognized type: ' . $fieldType);
        }

        $field = new static::$types[$fieldType]($key, $schemaOptions);
        $field->data['label'] = trans("{$schemaPrefix}.{$key}");

        return $field;
    }

    /**
     * Set options passed to the Vue input component handling search input.
     *
     * @param array|false $value
     * @param array $options
     */
    public function setSearch($value, array $options): void
    {
        $this->data['search']->init($value);
    }

    /**
     * Set whether the field should be editable or not.
     *
     * @param bool $value
     */
    public function setEditable(bool $value): void
    {
        $this->data['editable'] = $value;
    }

    /**
     * Set whether the field should be orderable or not.
     *
     * @param bool $value
     */
    public function setOrderable(bool $value): void
    {
        $this->data['orderable'] = $value;
    }

    /**
     * Set whether the field should be displayed or not.
     *
     * @param bool $value
     */
    public function setDisplayable(bool $value): void
    {
        $this->data['displayable'] = $value;
    }

    /**
     * Set CSS class applied to the DataTables column.
     *
     * @param string $value
     */
    public function setColumnClassName(string $value): void
    {
        $this->data['columnClassName'] = $value;
    }

    /**
     * Set database column name, if it differs from {$this->key}.
     *
     * @param string $value
     */
    public function setColumn(string $value): void
    {
        $this->data['column'] = $value;
    }

    /**
     * Set the column name for the view, if it differs from {$this->column}.
     *
     * @param string $value
     */
    public function setViewColumn(string $value): void
    {
        $this->data['viewColumn'] = $value;
    }

    /**
     * Set a default value (any datatype).
     *
     * @param mixed $value
     */
    public function setDefaultValue($value): void
    {
        $this->data['defaultValue'] = $value;
    }

    public function getColumn()
    {
        return $this->get('column', $this->key);
    }

    public function getViewColumn(): string
    {
        return $this->get('viewColumn', $this->getColumn());
    }

    public function getDefaultSearchOperator()
    {
        return $this->search->getDefaultOperator();
    }

    public function getModelAttribute(): string
    {
        return $this->get('modelAttribute', $this->key);
    }

    /**
     * Set help text shown when editing the field.
     *
     * @param string $value
     */
    public function setHelp(string $value): void
    {
        $this->data['help'] = $value;
    }

    /**
     * Set datatype.
     *
     * @param string $value
     */
    public function setDatatype(string $value): void
    {
        $this->data['datatype'] = $value;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Check if an attribute exist using "dot" notation.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * Get an attribute using "dot" notation.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * Dynamically retrieve attribute.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data[$key];
    }
}
