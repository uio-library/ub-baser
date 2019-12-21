<?php

namespace App\Dommer;

class DommerRecordView extends DommerRecord
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer_view';

    public static function refreshView()
    {
        \DB::unprepared('REFRESH MATERIALIZED VIEW dommer_view');
    }
}
