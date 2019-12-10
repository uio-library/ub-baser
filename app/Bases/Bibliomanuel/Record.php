<?php

namespace App\Bases\Bibliomanuel;

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
    protected $table = 'bibliomanuel';

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
