<?php

namespace App;

use Illuminate\Support\Arr;

abstract class BaseSchema implements \JsonSerializable
{
    public $prefix;

    protected $schema;

    protected $defaultOperators = [
        'eq',
        'neq',
        'isnull',
        'notnull',
    ];

    protected function init(array $options): void
    {
        foreach ($this->schema['fields'] as &$field) {
            $this->initField($field, $options);
        }
        foreach ($this->schema['groups'] as &$group) {
            foreach ($group['fields'] as &$field) {
                $this->initField($field, $options);
            }
        }
    }

    protected function initField(array &$field, array $options): void
    {
        // Add label
        $field['label'] = trans("{$this->prefix}.{$field['key']}");

        // Make fields searchable by default
        if (!Arr::has($field, 'search')) {
            Arr::set($field, 'search', []);
        }

        // Add default search operators if no operators set
        if ($field['search'] !== false && !Arr::has($field, 'search.operators')) {
            Arr::set($field, 'search.operators', $this->defaultOperators);
        }

        // Add autocomplete target if not set
        if (in_array($field['type'], ['autocomplete', 'persons', 'select', 'tags']) && !Arr::has($field, 'search.options.target')) {
            if (!Arr::has($options, 'autocompleTarget')) {
                throw new \RuntimeException('initSchema: Option autocompleTarget is missing!');
            }
            Arr::set($field, 'search.options.target', Arr::get($options, 'autocompleTarget'));
        }

        // Add min/max values for rangeslider if not set
        if ($field['type'] == 'rangeslider' && !Arr::has($field, 'search.options.minYear')) {
            if (!Arr::has($options, ['minYear', 'maxYear'])) {
                throw new \RuntimeException('initSchema: Options minYear and/or maxYear are missing!');
            }
            Arr::set($field, 'search.options.minYear', Arr::get($options, 'minYear'));
            Arr::set($field, 'search.options.maxYear', Arr::get($options, 'maxYear'));
        }
    }

    public function get(): array
    {
        return $this->schema;
    }

    public function flat(): array
    {
        $out = [];
        foreach ($this->schema['fields'] as &$field) {
            $out[] = $field;
        }
        foreach (Arr::get($this->schema, 'groups', []) as &$group) {
            foreach ($group['fields'] as &$field) {
                $out[] = $field;
            }
        }
        return $out;
    }

    public function keyed(): array
    {
        $fields = $this->flat();
        $out = [];
        foreach ($fields as &$field) {
            $out[$field['key']] = $field;
        }
        return $out;
    }

    public function keys(): array
    {
        return array_map(function ($col) {
            return $col['key'];
        }, $this->flat());
    }

    public function jsonSerialize()
    {
        return $this->schema;
    }

    public function __get($key)
    {
        return $this->schema[$key];
    }
}
