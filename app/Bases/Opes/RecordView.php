<?php

namespace App\Bases\Opes;

use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check()) {
            return parent::query()->where('public', 1);
        }
        return parent::query();
    }
}
