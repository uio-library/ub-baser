<?php

namespace App\Services;

use App\Base;
use App\Schema\SchemaField;

interface AutocompleteServiceInterface
{
    public function __construct(Base $base);

    public function complete(SchemaField $field, ?string $term): array;
}
