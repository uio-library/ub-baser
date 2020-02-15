<?php

namespace App\Schema;

class SelectField extends SchemaField
{
    public const TYPE = 'select';

    public function setDefaults()
    {
        // Defaults
        $this->data['multiple'] = false;
    }

    /**
     * Set allowed values.
     *
     * @param array $values
     */
    public function setValues($values): void
    {
        $this->data['values'] = $values;
    }

    /**
     * Set whether to accept multiple values (tags).
     *
     * @param bool $value
     */
    public function setMultiple($value): void
    {
        $this->data['multiple'] = $value;
    }

    public function formatValue($value)
    {
        if (isset($this->data['values'])) {
            foreach ($this->data['values'] as $option) {
                if ($option['id'] == $value) {
                    return $option['label'];
                }
            }

            return "<span class=\"text-danger\">$value</span>";
        }
        return $value;
    }
}
