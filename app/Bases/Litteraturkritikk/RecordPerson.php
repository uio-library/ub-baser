<?php

namespace App\Bases\Litteraturkritikk;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RecordPerson extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_record_person';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'person_role' => 'array',
    ];
}
