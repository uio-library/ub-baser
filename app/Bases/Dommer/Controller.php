<?php

namespace App\Bases\Dommer;

use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{
    protected $logGroup = 'dommer';

    public static $defaultColumns = [
        'navn',
        'kilde',
        'aar',
        'side',
    ];

    public static $defaultSortOrder = [
        ['key' => 'aar', 'direction' => 'desc'],
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
            'navn'     => 'required|unique:dommer,navn' . ($record->id === null ? '' : ',' . $record->id) . '|max:255',
            'aar'      => 'required|digits:4',
            'side'     => 'required|numeric|min:1|max:9999',
            'kilde'    => 'required|numeric',
        ];
    }
}
