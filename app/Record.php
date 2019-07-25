<?php

namespace App;

use Illuminate\Support\Arr;

abstract class Record extends \Eloquent
{
    public static $prefix;

    protected static function initSchema(array &$schema, array $options): void
    {
        foreach ($schema['fields'] as &$field) {
            static::initField($field, $options);
        }
        foreach ($schema['groups'] as &$group) {
            foreach ($group['fields'] as &$field) {
                static::initField($field, $options);
            }
        }
    }

    protected static function initField(&$field, $options): void
    {
        // Add label
        $field['label'] = trans(static::$prefix . '.' . $field['key']);

        // Make fields searchable by default
        if (!Arr::has($field, 'search')) {
            Arr::set($field, 'search', []);
        }

        // Add default operators if not set
        if ($field['search'] !== false && !isset($field['search']['operators'])) {
            $field['search']['operators'] = [
                'eq',
                'neq',
                'isnull',
                'notnull',
            ];
        }

        // Add autocomplete target if not set
        if ($field['type'] == 'autocomplete' && !Arr::has($field, 'search.options.target')) {
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

    public static function getSchema(): array
    {
        // Override me
        return [];
    }

    public static function getFlatSchema(): array
    {
        $columns = static::getSchema();
        $out = [];
        foreach ($columns['fields'] as &$field) {
            $out[] = $field;
        }
        foreach (Arr::get($columns, 'groups', []) as &$group) {
            foreach ($group['fields'] as &$field) {
                $out[] = $field;
            }
        }
        return $out;
    }

    public static function getSchemaByKey(): array
    {
        $columns = static::getFlatSchema();
        $out = [];
        foreach ($columns as &$field) {
            $out[$field['key']] = $field;
        }
        return $out;
    }

    public static function getKeys(): array
    {
        return array_map(function($col) {
            return $col['key'];
        }, static::getFlatSchema());
    }

}
