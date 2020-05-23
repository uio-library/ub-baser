<?php

namespace App\Bases\Bibsys;

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

    public function getFormattedMarcRecord()
    {
        return preg_replace(
            ['/^\*/m', '/\n/', '/\$([a-z0-9])/'],
            ['', '<br>', '<span style="font-weight: bold">$\1 </span>'],
            $this->marc_record
        );
    }
}
