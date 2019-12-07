<?php

namespace App\Bibsys;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bibsys';
}