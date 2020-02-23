<?php

namespace App\Schema;

use App\Base;

class SelectField extends SchemaField
{
    public const TYPE = 'select';

    public $operators = [
        Operators::IS,
        Operators::NOT,
        Operators::IS_NULL,
        Operators::NOT_NULL,
    ];

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

    public function formatBadge($value, Base $base, $badgeType='badge-primary')
    {
        $url = $base->action('index', [
            'f0' => $this->getColumn(),
            'v0' => $value,
        ]);
        return "<a class=\"badge $badgeType\" href=\"$url\">$value</a>";
    }


    public function formatValue($value, Base $base)
    {
        if (!is_array($value)) {
            if (isset($this->data['values'])) {
                foreach ($this->data['values'] as $option) {
                    if ($option['value'] == $value) {
                        return $option['prefLabel'];
                    }
                }
                return '<span class="text-danger">' . $value . '</span>';
            }
            return $value;
        }

        $value = array_map(
            function($val) use ($base) {
                if (isset($this->data['values'])) {
                    foreach ($this->data['values'] as $option) {
                        if ($option['value'] == $val) {
                            return $this->formatBadge($option['prefLabel'], $base, 'badge-primary');
                        }
                    }
                    return $this->formatBadge($val, $base, 'badge-danger');
                } else {
                    return $this->formatBadge($val, $base, 'badge-primary');
                }
            },
            $value
        );

        return implode(' ', $value);
    }
}
