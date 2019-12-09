<?php

namespace App\Http\Requests;

use App\Bibsys\BibsysSchema;
use App\Bibsys\BibsysView;
use App\Schema\Schema;
use Illuminate\Database\Eloquent\Builder;

class BibsysSearchRequest extends SearchRequest
{
    protected function getSchema(): Schema
    {
        return app(BibsysSchema::class);
    }

    protected function makeQueryBuilder(): Builder
    {
        return BibsysView::query();
    }
}
