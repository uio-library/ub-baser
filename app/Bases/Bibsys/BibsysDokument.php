<?php

namespace App\Bases\Bibsys;

use App\Record;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BibsysDokument extends Record
{
    use HasFactory;

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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static $prefix = 'bibsys';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bibsys_dok';

    public function objektPost()
    {
        return $this->belongsTo(BibsysObjekt::class, 'objektid');
    }

    public function seriePost()
    {
        return $this->belongsTo(BibsysDokument::class, 'dokid', 'seriedokid');
    }

    public function serieMedlemmer()
    {
        return $this->hasMany(BibsysDokument::class, 'seriedokid', 'dokid');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return "Viser {$this->objektid} + {$this->dokid }";
    }
}
