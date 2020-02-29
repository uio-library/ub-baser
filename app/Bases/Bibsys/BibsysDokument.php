<?php

namespace App\Bases\Bibsys;

use App\Record;

class BibsysDokument extends Record
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dokid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public static $prefix = 'bibsys';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bibsys_dok';

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'objektid');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return "Viser {$this->objektid} + {$this->dokid }";
    }
}
