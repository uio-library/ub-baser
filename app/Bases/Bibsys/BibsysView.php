<?php

namespace App\Bases\Bibsys;

use App\Bases\Interfaces\RecordViewInterface;

class BibsysView extends BibsysDokument implements RecordViewInterface
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

    public static function refreshView()
    {
        \DB::unprepared('REFRESH MATERIALIZED VIEW bibsys_search');
    }

    public function getFormattedMarcRecord()
    {
        return preg_replace(
            ['/^\*/m', '/\n/', '/\$([a-z0-9])/'],
            ['', '<br>', '<span style="font-weight: bold">$\1 </span>'],
            $this->marc_record
        );
    }
}
