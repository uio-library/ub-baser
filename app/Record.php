<?php

namespace App;

abstract class Record extends \Eloquent
{
    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    abstract public function getTitle(): string;
}
