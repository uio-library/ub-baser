<?php

namespace App\Bases\Litteraturkritikk;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class PersonContribution extends MorphPivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_person_contributions';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'person_role' => 'array',
    ];
}
