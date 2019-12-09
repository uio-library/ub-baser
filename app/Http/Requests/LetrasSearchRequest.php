<?php

namespace App\Http\Requests;

use App\Letras\LetrasRecord;
use App\Letras\LetrasSchema;
use App\Schema\Schema;
use Illuminate\Database\Eloquent\Builder;

class LetrasSearchRequest extends SearchRequest
{
    protected function getSchema(): Schema
    {
        return app(LetrasSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return LetrasRecord::query();
    }
}
