<?php

namespace App\Exceptions;

use Exception;

class UnsupportedSearchOperator extends Exception
{
    public $operator;
    public $field;

    public function __construct(string $operator, string $field)
    {
        parent::__construct(sprintf(
            'Search operator "%s" not supported with field "%s"',
            htmlspecialchars($operator),
            htmlspecialchars($field)
        ));

        $this->operator = $operator;
        $this->field = $field;
    }
}
