<?php

namespace App\Schema;

use Illuminate\Support\Arr;

class SchemaGroup implements \JsonSerializable
{
    public $key;
    public $showInRecordView;
    public $searchable;
    public $fields;
    public $label;

    public static function make(array $data, string $schemaPrefix): self
    {
        $schemaGroup = new self();
        $schemaGroup->key = $data['key'];
        $schemaGroup->showInRecordView = Arr::get($data, 'showInRecordView', true);
        $schemaGroup->searchable = Arr::get($data, 'searchable', true);
        $schemaGroup->fields = SchemaFields::make($data['fields'], $schemaPrefix);
        $schemaGroup->label = trans("{$schemaPrefix}.{$schemaGroup->key}");

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
            'key' => $this->key,
            'showInRecordView' => $this->showInRecordView,
            'searchable' => $this->searchable,
            'fields' => $this->fields,
            'label' => $this->label,
        ];
    }
}
