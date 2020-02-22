<?php

namespace App\Console\Commands;

class ImportBibliomanuelCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:bibliomanuel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Bibliografi om Manuel"';

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
        'bibliomanuel',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'bibliomanuel.id',
    ];
}
