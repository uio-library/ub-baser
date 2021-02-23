<?php

namespace App\Bases\Opes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Edition extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_editions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'corrections' => 'array',  // not ready yet
        // 'bibliography' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    /**
     * Get the record the publication belongs to.
     */
    public function record()
    {
        return $this->belongsTo(Record::class, 'opes_id');
    }

    public function recordView()
    {
        return $this->belongsTo(RecordView::class, 'opes_id');
    }

    public function getTitle()
    {
        $picture = empty($this->photo) ? '' : ', picture: ' . $this->photo;
        return "{$this->editor}, {$this->ser_vol}, {$this->year}, {$this->pg_no}{$picture}";
    }

    public function getStringRepresentationAttribute()
    {
        return $this->getTitle();
    }

    public function __toString()
    {
        return $this->getStringRepresentationAttribute();
    }

    public function correctionsArray()
    {
        return empty($this->corrections) ? [] : explode('; ', $this->corrections);
    }
}
