<?php

namespace App\Http\Requests;

use App\BaseSchema;
use App\Dommer\DommerRecordView;
use App\Dommer\DommerSchema;
use Illuminate\Database\Eloquent\Builder;

class DommerSearchRequest extends SearchRequest
{
    protected function getSchema(): BaseSchema
    {
        return app(DommerSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return DommerRecordView::query();
    }
}
