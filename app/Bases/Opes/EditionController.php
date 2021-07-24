<?php

namespace App\Bases\Opes;

use App\Http\Controllers\BaseController;
use App\Record;

class EditionController extends BaseController
{
    protected $logGroup = 'opes';
    protected $model = 'Edition';
    protected $showView = 'editions.show';
    protected $editView = 'editions.edit';

    /**
     * Validation rules when creating or updating a record.
     * @see: https://laravel.com/docs/master/validation
     *
     * @param Record $record
     * @return array
     */
    protected function getValidationRules(Record $record): array
    {
        return [];
    }
}
