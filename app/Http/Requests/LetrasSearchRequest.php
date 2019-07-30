<?php

namespace App\Http\Requests;

use App\BaseSchema;
use App\Letras\LetrasSchema;
use App\Letras\LetrasRecord;
use Illuminate\Database\Eloquent\Builder;

class LetrasSearchRequest extends SearchRequest
{
    protected function getSchema(): BaseSchema
    {
        return app(LetrasSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return LetrasRecord::query();
    }
}
