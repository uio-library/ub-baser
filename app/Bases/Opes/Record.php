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
        'people' => 'array',
        'places' => 'array',
        'subjects' => 'array',
        'bibliography' => 'array',
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
     * Get the editions for this record.
     */
    public function editions()
    {
        return $this->hasMany(Edition::class, 'opes_id')
            ->orderBy('edition_nr');
    }

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->inv_no;  // TODO: standard_designation
        // return sprintf('%s (%s)', $this->tittel2, $this->utgivelsesaar2);
    }
}
