<?php

namespace App\Schema;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class SchemaField implements JsonSerializable
{
    const TYPE = null;

    public $schemaPrefix;

    public $data = [];

    public $parent = null;

    public $operators = [
        Operators::CONTAINS,
        Operators::NOT_CONTAINS,
        Operators::EQUALS,
        Operators::NOT_EQUALS,
        Operators::BEGINS_WITH,
        Operators::ENDS_WITH,
        Operators::IS_NULL,
        Operators::NOT_NULL,
    ];

    public static $types = [
        'autocomplete' => AutocompleteField::class,
        'boolean' => BooleanField::class,
        'date' => DateField::class,
        'incrementing' => IncrementingField::class,
        'entities' => EntitiesField::class,
        'search_only' => SearchOnlyField::class,
        'select' => SelectField::class,
        'simple' => SimpleField::class,
        'url' => UrlField::class,
    ];

    public function __construct(string $key, string $schemaPrefix)
    {
        $keyParts = explode(':', $key);
        $this->schemaPrefix = $schemaPrefix;
        $this->data['key'] = $key;
        $this->data['shortKey'] = $keyParts[count($keyParts) - 1];
        $this->data['type'] = static::TYPE;

        // Defaults
        $this->data['showInTableView'] = true;
        $this->data['showInRecordView'] = true;
        $this->data['orderable'] = true;
        $this->data['defaultValue'] = null;
        $this->data['datatype'] = Schema::DATATYPE_STRING;
        $this->data['search'] = new SearchOptions($key, static::TYPE, $this->operators);
        $this->data['edit'] = new EditOptions();

        $this->setDefaults();
    }

    /**
     * Set default value. Override this.
     */
    public function setDefaults()
    {
    }

    /**
     * Factory method to initialize a schema field from JSON data.
     *
     * @param array $data
     * @param string $schemaPrefix
     * @param SchemaField $parent
     * @return SchemaField
     */
    public static function make(array $data, string $schemaPrefix, SchemaField $parent = null): self
    {
        $field = static::newFieldFromType(
            $data['type'],
            $schemaPrefix,
            $data['key'],
            $parent
        );

        foreach ($data as $key => $value) {
            if (in_array($key, ['type', 'key'])) {
                // pass
            } elseif (method_exists($field, 'set' . Str::ucfirst($key))) {
                $field->{'set' . Str::ucfirst($key)}($value);
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
     * @param SchemaField|null $parent
     * @return mixed
     */
    public static function newFieldFromType(
        string $fieldType,
        string $schemaPrefix,
        string $key,
        SchemaField $parent = null
    ): self {
        if (!isset(static::$types[$fieldType])) {
            throw new \RuntimeException('Schema contains field of unrecognized type: ' . $fieldType);
        }

        $field = new static::$types[$fieldType]($key, $schemaPrefix);
        $localizedLabel = trans("{$schemaPrefix}.{$key}");
        if (is_array($localizedLabel)) {
            $localizedLabel = Arr::get($localizedLabel, '_');
        }
        $field->data['label'] = $localizedLabel;
        $field->setParent($parent);

        return $field;
    }

    public function setParent(SchemaField $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * Set options passed to the Vue input component handling search input.
     *
     * @param array|false $value
     */
    public function setSearch($value): void
    {
        $this->data['search']->init($value);
    }

    /**
     * Set whether the field should be edit or not.
     *
     * @param array|bool $value
     */
    public function setedit($value): void
    {
        $this->data['edit']->init($value);
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
     * Set whether the field should be displayed in table view or not.
     *
     * @param bool $value
     */
    public function setShowInTableView(bool $value): void
    {
        $this->data['showInTableView'] = $value;
    }

    /**
     * Set whether the field should be displayed in record view or not.
     *
     * @param bool $value
     */
    public function setShowInRecordView(bool $value): void
    {
        $this->data['showInRecordView'] = $value;
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

    public function getSortColumn()
    {
        return $this->search->sort_index ?: $this->getViewColumn();
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
