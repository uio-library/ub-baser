<?php

namespace App\Http\Requests;

use App\Dommer\DommerRecordView;

class DommerSearchRequest extends SearchRequest
{
    protected function getFields()
    {
        return DommerRecordView::getSchemaByKey();
    }

    protected function makeQueryBuilder()
    {
        return DommerRecordView::query();
    }
}
