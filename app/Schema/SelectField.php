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

    public function formatValue($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        if (isset($this->data['values'])) {
            $values = array_map(
                function($value) {
                    foreach ($this->data['values'] as $option) {
                        if ($option['value'] == $value) {
                            return $option['prefLabel'];
                        }
                    }
                    return "<span class=\"text-danger\">$value</span>";
                },
                $values
            );
        }

        return implode(', ', $values);
    }
}
