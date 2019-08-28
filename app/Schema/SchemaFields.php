<?php

namespace App\Schema;

class SchemaFields
{
    public static function make(array $fieldsData, string $schemaPrefix, array $schemaOptions)
    {
        $out = [];

        foreach ($fieldsData as $field) {
            $out[] = SchemaField::make($field, $schemaPrefix, $schemaOptions);
        }

        return $out;
    }
}
