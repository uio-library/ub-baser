<?php

namespace App\Bases\Dommer;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param Request $request
     * @param Schema $schema
     * @param Record $record
     * @return array
     */
    protected function updateOrCreate(Request $request, Schema $schema, Record $record)
    {
        return parent::updateOrCreateRecord($request, $schema, $record);
    }
}
