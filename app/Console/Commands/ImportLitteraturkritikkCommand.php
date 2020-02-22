<?php

namespace App\Console\Commands;

use App\Bases\Litteraturkritikk\Person;
use App\Bases\Litteraturkritikk\Record;
use Illuminate\Support\Arr;
use Punic\Language;

class ImportLitteraturkritikkCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:litteraturkritikk';

    /**
     * Import file format.
     *
     * @var string
     */
    protected $fileFormat = 'csv';

    /**
     * Tables to import.
     *
     * @var string[]
     */
    protected $tables = [
        'litteraturkritikk_records',
        'litteraturkritikk_personer',
        'litteraturkritikk_record_person',
        'litteraturkritikk_kritikktyper',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'litteraturkritikk_records.id',
        'litteraturkritikk_personer.id',
        'litteraturkritikk_record_person.id',
        'litteraturkritikk_kritikktyper.id',
    ];

    /**
     * Views to refresh.
     *
     * @var string[]
     */
    protected $views = [
        'litteraturkritikk_records_search',
    ];
}
