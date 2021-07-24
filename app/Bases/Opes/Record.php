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
        // 'bibliography' => 'array', // Vent
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
        return $this->standard_designation;
        // return sprintf('%s (%s)', $this->tittel2, $this->utgivelsesaar2);
    }

    public function bibliographyArray()
    {
        return empty($this->bibliography) ? [] : explode('; ', $this->bibliography);
    }

    public function getFormattedValue($key, $value, $base)
    {
        $value = strip_tags($value);
        return preg_replace_callback('/\[\[([^\]]+)\]\]/', function ($matches) use ($key , $base) {
            return sprintf(
                '<a href="%s">%s</a>',
                $base->action('index', ['q' => $key . ' contains ' . $matches[1]]),
                $matches[1]
            );
        }, $value);
    }

    /**
     * Se RecordView for string representation.
     */
}
