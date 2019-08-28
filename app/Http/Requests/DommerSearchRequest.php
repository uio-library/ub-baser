<?php

namespace App\Http\Requests;

use App\Schema\Schema;
use App\Dommer\DommerRecordView;
use App\Dommer\DommerSchema;
use Illuminate\Database\Eloquent\Builder;

class DommerSearchRequest extends SearchRequest
{
    protected function getSchema(): Schema
    {
        return app(DommerSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return DommerRecordView::query();
    }
}
