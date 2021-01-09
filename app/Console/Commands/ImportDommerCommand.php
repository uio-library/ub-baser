<?php

namespace App\Console\Commands;

class ImportDommerCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:dommer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Dommers populærnavn"';

    /**
     * Import file format.
     *
     * @var string
     */
    protected $fileFormat = 'tsv';

    /**
     * Tables to import.
     *
     * @var string[]
     */
    protected $tables = [
        'dommer_kilder',
        'dommer',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'dommer_kilder.id',
        'dommer.id',
    ];

    /**
     * Views to refresh.
     *
     * @var string[]
     */
    protected $views = [
        'dommer_view',
    ];
}
