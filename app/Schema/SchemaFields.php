<?php

namespace App\Schema;

class SchemaFields
{
    public static function make(array $fieldsData, string $schemaPrefix)
    {
        $out = [];

        foreach ($fieldsData as $field) {
            $out[] = SchemaField::make($field, $schemaPrefix);
        }

        return $out;
    }
}
