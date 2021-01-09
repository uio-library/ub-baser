<?php

namespace App\Schema;

class SimpleField extends SchemaField
{
    public const TYPE = 'simple';

    public function setDefaults()
    {
        // Defaults
        $this->data['multiline'] = false;
    }

    /**
     * Set whether to accept multiline text.
     *
     * @param bool $value
     */
    public function setMultiline($value): void
    {
        $this->data['multiline'] = $value;
    }
}
