<?php

namespace App\Http\Requests;

use App\Schema\Schema;
use App\Letras\LetrasSchema;
use App\Letras\LetrasRecord;
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
