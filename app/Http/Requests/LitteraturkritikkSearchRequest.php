<?php

namespace App\Http\Requests;

use App\Schema\Schema;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use App\Litteraturkritikk\RecordView;
use Illuminate\Database\Eloquent\Builder;

class LitteraturkritikkSearchRequest extends SearchRequest
{
    protected function getSchema(): Schema
    {
        return app(LitteraturkritikkSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return RecordView::query();
    }
}
