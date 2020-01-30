<?php

namespace App\Bases\Litteraturkritikk;

class RecordView extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_records_search';

    public static function refreshView()
    {
        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'verk_spraak' => 'array',
        'verk_originalspraak' => 'array',
    ];

}
