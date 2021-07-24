<?php

namespace App\Bases\Nordskrift;

use App\Record as BaseRecord;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends BaseRecord
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nordskrift';

    /**
     * The attributes that are mass assignable. This should include all the standard
     * attributes of this model, excluding auto-generated ones like 'id', 'created_at' etc.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return sprintf('Post #%s', $this->id);
    }
}
