<?php

namespace App\Bases\Opes;

use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{
    protected $logGroup = 'opes';

    public static $defaultColumns = [
        'inv_no',
        'title_or_type',
        'genre',
        'date',
        'origin',
        'fullsizefront_r1',
        'fullsizeback_r1',
    ];

    public static $defaultSortOrder = [
        ['key' => 'inv_no', 'direction' => 'asc'],
    ];

    /**
     * Validation rules when creating or updating a record.
     * @see: https://laravel.com/docs/master/validation
     *
     * @param Model $record
     * @return array
     */
    protected function getValidationRules(Model $record): array
    {
        return [
            'inv_no' => 'required',
            'language_code' => 'size:3',
            'items' => 'min:1',
            'negative_in_copenhagen' => 'boolean',
            'date_cataloged' => 'date_format:Y-m-d',
        ];
    }
}
