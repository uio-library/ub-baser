<?php

namespace App\Bases\Letras;

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
    protected $table = 'letras';

    /**
     * The attributes that are mass assignable. This should include all the standard
     * attributes of this model, excluding auto-generated ones like 'id', 'created_at' etc.
     *
     * @var array
     */
    protected $fillable = [
        'forfatter',
        'land',
        'tittel',
        'utgivelsesaar',
        'sjanger',
        'oversetter',
        'tittel2',
        'utgivelsessted',
        'utgivelsesaar2',
        'forlag',
        'foretterord',
        'spraak',
    ];

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return sprintf('Post #%s', $this->id);
        // return sprintf('%s (%s)', $this->tittel2, $this->utgivelsesaar2);
    }
}
