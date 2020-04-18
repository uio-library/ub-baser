<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Record extends Model
{
    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    abstract public function getTitle(): string;

    public function isEmpty($field)
    {
        $value = $this->{$field};
        if (is_object($value) && is_a($value, Collection::class)) {
            return $value->count() == 0;
        }

        return empty($value);
    }
}
