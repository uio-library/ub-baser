<?php

namespace App\Schema;

/**
 * @property string type
 * @property string table
 * @property string source_id
 * @property string target_id
 * @property string target_type
 * @property string source_type
 */
class Relationship extends FieldOptions
{
    /**
     * Default data
     */
    public $data = [

        // ??
        'type' => 'many',

        // Table holding the relationships
        'table' => null,

        // Column holding the source record id
        'source_id' => 'record_id',

        // Column holding the source record type, for polymorphic relationships
        'source_type' => null,

        // Column holding the target record id
        'target_id' => null,

        // Column holding the target record type, for polymorphic relationships
        'target_type' => null,
    ];

    /**
     * Valid properties and their datatypes
     */
    protected $properties = [
        'type' => 'string',
        'table' => 'string',
        'source_id' => 'string',
        'source_type' => 'string',
        'target_id' => 'string',
        'target_type' => 'string',
    ];
}
