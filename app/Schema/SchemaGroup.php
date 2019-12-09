<?php

namespace App\Schema;

class SchemaGroup implements \JsonSerializable
{
    public $label;
    public $fields;

    public static function make(array $data, string $schemaPrefix, array $options): self
    {
        $schemaGroup = new self();
        $schemaGroup->label = $data['label'];
        $schemaGroup->fields = SchemaFields::make($data['fields'], $schemaPrefix, $options);

        return $schemaGroup;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'label' => $this->label,
            'fields' => $this->fields,
        ];
    }
}
