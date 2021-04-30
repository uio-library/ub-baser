<?php

namespace App\Console\Commands;

class ImportBibliofremmedspraakCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:bibliofremmedspraak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Bibliografi om fremmedspraak"';

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
        'bibliofremmedspraak',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'bibliofremmedspraak.id',
    ];
}
