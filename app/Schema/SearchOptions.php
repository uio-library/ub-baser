<?php

namespace App\Schema;

use Illuminate\Support\Arr;

class SearchOptions extends FieldOptions
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

    public function getDefaultOperator()
    {
        return $this->data['operators'][0] ?? null;
    }
}
