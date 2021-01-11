<?php

namespace App\Bases\Opes;

use App\Http\Controllers\BaseController;

class EditionController extends BaseController
{
    protected $logGroup = 'opes';
    protected $model = 'Edition';
    protected $recordSchema = 'EditionSchema';
    protected $showView = 'editions.show';
    protected $editView = 'editions.edit';
}
