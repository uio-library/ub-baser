<?php

namespace App\Schema;

class EnumField extends SchemaField
{
    public const TYPE = 'enum';

    /**
     * Set allowed values.
     *
     * @param array $values
     */
    public function setValues($values): void
    {
        $this->data['values'] = $values;
    }

    public function formatValue($id)
    {
        foreach ($this->data['values'] as $value) {
            if ($value['id'] == $id) {
                return $value['label'];
            }
        }

        return "<span class=\"bg-danger text-white p-1\">$id</span> <em>(Ugyldig verdi)</em>";
    }
}
