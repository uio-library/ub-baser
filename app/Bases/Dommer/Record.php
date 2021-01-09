<?php

namespace App\Bases\Dommer;

use App\Record as BaseRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends BaseRecord
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer';

    /**
     * Get the periodical this record belongs to.
     */
    public function kilde()
    {
        return $this->belongsTo(DommerKilde::class);
    }

    public function link()
    {
        return action([Controller::class, 'show'], $this->id);
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // To simplify editing, we cast these as strings
        'side' => 'string',
        'aar' => 'string',
    ];

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->navn ?: '#' . $this->id;
    }
}
