<?php

namespace App\Schema;

class Operators
{
    const CONTAINS = 'contains';  # Case insensitive
    const NOT_CONTAINS = 'notcontains';  # Case insensitive

    const BEGINS_WITH = 'begins';
    const ENDS_WITH = 'ends';

    const EQUALS = 'eq';
    const NOT_EQUALS = 'ne';

    const IS = 'is';
    const NOT = 'not';

    const IN_RANGE = 'in';
    const OUTSIDE_RANGE = 'outside';

    const IS_NULL = 'isnull';
    const NOT_NULL = 'notnull';

    const LIKE = 'like';  # Case sensitive
}
