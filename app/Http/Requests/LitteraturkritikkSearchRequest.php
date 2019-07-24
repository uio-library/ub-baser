<?php

namespace App\Http\Requests;

use App\Litteraturkritikk\Record;
use App\Litteraturkritikk\RecordView;

class LitteraturkritikkSearchRequest extends SearchRequest
{
    protected function getFields()
    {
        return Record::getSchemaByKey();
    }

    protected function makeQueryBuilder()
    {
        return RecordView::query();
    }
}
