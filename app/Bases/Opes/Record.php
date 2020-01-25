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
        return sprintf('P.Oslo %s', $this->inv_no);
        // return sprintf('%s (%s)', $this->tittel2, $this->utgivelsesaar2);
    }

    public function nextRecord(): int
    {
        $rec = self::where('id', '>', $this->id)
            ->orderBy('id', 'asc')
            ->limit(1)
            ->select('id')
            ->first();
        if (is_null($rec)) {
            $rec = self::orderBy('id', 'asc')
                ->select('id')
                ->first();
        }

        return $rec->id;
    }

    public function prevRecord(): int
    {
        $rec = self::where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->first();
        if (is_null($rec)) {
            $rec = self::orderBy('id', 'desc')
                ->select('id')
                ->first();
        }

        return $rec->id;
    }
}
