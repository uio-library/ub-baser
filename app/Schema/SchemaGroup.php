<?php

namespace App\Schema;

use Illuminate\Support\Arr;

class SchemaGroup implements \JsonSerializable
{
    public $label;
    public $displayable;
    public $searchable;
    public $fields;

    public static function make(array $data, string $schemaPrefix): self
    {
        $schemaGroup = new self();
        $schemaGroup->label = $data['label'];
        $schemaGroup->displayable = Arr::get($data, 'displayable', true);
        $schemaGroup->searchable = Arr::get($data, 'searchable', true);
        $schemaGroup->fields = SchemaFields::make($data['fields'], $schemaPrefix);

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
            'displayable' => $this->displayable,
            'searchable' => $this->searchable,
            'fields' => $this->fields,
        ];
    }
}
