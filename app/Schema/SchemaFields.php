<?php

namespace App\Schema;

class SchemaFields
{
    public static function make(array $fieldsData, string $schemaPrefix, SchemaField $parent = null)
    {
        $out = [];

        foreach ($fieldsData as $field) {
            $out[] = SchemaField::make($field, $schemaPrefix, $parent);
        }

        return $out;
    }
}
