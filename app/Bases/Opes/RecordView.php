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
            return parent::query()->where('public', '=', true);
        }
        return parent::query();
    }

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        if ($this->standard_designation === 'P.Oslo inv. ' . $this->inv_no) {
            return $this->standard_designation;
        } else {
            return $this->standard_designation . ' (inv. ' . $this->inv_no . ')';
        }
    }

    public function getStringRepresentationAttribute()
    {
        return $this->getTitle();
    }

    public function __toString()
    {
        return $this->getStringRepresentationAttribute();
    }
}
