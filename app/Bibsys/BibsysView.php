<?php

namespace App\Bibsys;

class BibsysView extends BibsysObjekt
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dokid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bibsys_search';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
