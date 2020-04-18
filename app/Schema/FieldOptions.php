<?php

namespace App\Schema;

use JsonSerializable;

abstract class FieldOptions implements JsonSerializable
{
    /**
     * Default data
     */
    public $data = [
        'enabled' => true,
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'enabled' => 'boolean',
    ];

    public function init($options)
    {
        if ($options === false) {
            $this->data['enabled'] = false;
            return;
        }

        foreach ($options as $key => $value) {
            if (!isset($this->properties[$key])) {
                throw new \RuntimeException(get_class($this) . ': Unknown schema attribute "' . $key . '"');
            }

            if (gettype($value) !== $this->properties[$key]) {
                throw new \RuntimeException(get_class($this) . ': Invalid datatype for schema attribute "' . $key . '"');
            }

            $this->data[$key] = $value;
        }
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

    /**
     * Dynamically modify attribute.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        return $this->data[$key] = $value;
    }
}
