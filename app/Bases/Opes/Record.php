<?php

namespace App\Bases\Opes;

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
    protected $table = 'opes';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'persons' => 'array',
        'subj_headings' => 'array',
    ];

    /**
     * The attributes that are mass assignable. This should include all the standard
     * attributes of this model, excluding auto-generated ones like 'id', 'created_at' etc.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * Get the publications for this record.
     */
    public function publications()
    {
        return $this->hasMany(Publication::class, 'opes_id');
    }

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->inv_no;
        // return sprintf('%s (%s)', $this->tittel2, $this->utgivelsesaar2);
    }
}
