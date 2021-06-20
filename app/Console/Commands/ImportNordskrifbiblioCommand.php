<?php

namespace App\Console\Commands;

class ImportNordskrifbiblioCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:nordskrifbiblio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Nordisk Skrift Bibliografi"';

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
        'nordskrifbiblio',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'nordskrifbiblio.id',
    ];
}
