<?php

namespace App\Bases\Bibsys;

use App\Record;

class BibsysObjekt extends Record
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'objektid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bibsys';

    public function dokumentPoster()
    {
        return $this->hasMany(BibsysDokument::class, 'objektid');
    }

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '#' . $this->id;
    }
}
