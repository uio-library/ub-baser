<?php

namespace App\Schema;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsonSerializable;

class SearchConfig implements JsonSerializable
{
    /**
     * Default data
     */
    public $data = [
        'enabled' => true,
        'advanced' => false,
        'placeholder' => '',
        'operators' => [],
        'type' => 'simple', // 'ts', 'simple', 'range', 'array', ...
        'widget' => 'simple',
        'widgetOptions' => [],
        'case' => null,  // Either Schema::UPPER_CASE or Schema::LOWER_CASE
        'index' => null,
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'enabled' => 'boolean',
        'advanced' => 'boolean',
        'placeholder' => 'string',
        'operators' => 'array',
        'type' => 'string',
        'widget' => 'string',
        'widgetOptions' => 'array',
        'case' => 'string',
        'index' => 'string',
    ];

    public function __construct($key, $fieldType, $defaults)
    {
        $this->data['index'] = $key;
        $this->data['type'] = $fieldType;
        $this->data['widget'] = $fieldType;
        $this->data['operators'] = Arr::get($defaults, 'defaultOperators', []);
    }

    public function init($options)
    {
        if ($options === false) {
            $this->data['enabled'] = false;
            return;
        }

        foreach ($options as $key => $value) {
            if (!isset($this->properties[$key])) {
                throw new \RuntimeException('Unknown search schema attribute: "' . $key . '"');
            }

            if (gettype($value) !== $this->properties[$key]) {
                throw new \RuntimeException('Invalid datatype for search schema attribute: "' . $key . '"');
            }

            $this->data[$key] = $value;
        }
    }

    public function getDefaultOperator()
    {
        return $this->data['operators'][0] ?? null;
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
