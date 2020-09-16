<?php

namespace App\Bases\Opes;

use App\Http\Controllers\BaseController;
use App\Http\Request;
use App\Record as BaseRecord;
use App\Base;
use Illuminate\Http\RedirectResponse;

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
     * @param Record $record
     * @return array
     */
    protected function getValidationRules(BaseRecord $record): array
    {
        return [
            'inv_no' => 'required',
            'language_code' => 'size:3',
            'items' => 'min:1',
            'negative_in_copenhagen' => 'boolean',
            'date_cataloged' => 'date_format:Y-m-d',
        ];
    }

    /**
     * Publish the specified resource.
     *
     * @param Request $request
     * @param Base $base
     * @param Record $record
     * @return RedirectResponse
     */
    public function publish(Request $request, Base $base, Record $record)
    {
        $record->public = 1;
        $record->save();

        $url = $base->action('show', $record->id);
        $this->log(
            'Publiserte <a href="%s">post #%s</a>.',
            $url,
            $record->id
        );
        return redirect($url)->with('status', trans('base.notification.recordpublished'));
    }

    /**
     * Unpublish the specified resource.
     *
     * @param Request $request
     * @param Base $base
     * @param Record $record
     * @return RedirectResponse
     */
    public function unpublish(Request $request, Base $base, Record $record)
    {
        $record->public = 0;
        $record->save();

        $url = $base->action('show', $record->id);
        $this->log(
            'Avpubliserte <a href="%s">post #%s</a>.',
            $url,
            $record->id
        );
        return redirect($url)->with('status', trans('base.notification.recordunpublished'));
    }
}
