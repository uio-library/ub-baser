<?php

namespace App\Http\Requests;

use App\BaseSchema;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use App\Litteraturkritikk\RecordView;
use Illuminate\Database\Eloquent\Builder;

class LitteraturkritikkSearchRequest extends SearchRequest
{
    protected function getSchema(): BaseSchema
    {
        return app(LitteraturkritikkSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return RecordView::query();
    }
}
