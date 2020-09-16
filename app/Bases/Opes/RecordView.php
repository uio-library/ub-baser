<?php

namespace App\Bases\Opes;

class RecordView extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_view';

    public static function refreshView()
    {
        \DB::unprepared('REFRESH MATERIALIZED VIEW opes_view');
    }

    public static function query()
    {
        return parent::query()->where('public', 1);
    }
}
