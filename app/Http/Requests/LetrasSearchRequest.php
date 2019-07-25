<?php

namespace App\Http\Requests;

use App\LetrasRecord;

class LetrasSearchRequest extends SearchRequest
{
    protected function getFields()
    {
        return LetrasRecord::getSchemaByKey();
    }

    protected function makeQueryBuilder()
    {
        return LetrasRecord::query();
    }
}
