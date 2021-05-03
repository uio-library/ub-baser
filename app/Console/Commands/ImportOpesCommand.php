<?php

namespace App\Console\Commands;

class ImportOpesCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:opes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Opes"';

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
        'opes',
        'opes_editions',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'opes.id',
        'opes_editions.id',
    ];

    /**
     * Views to refresh.
     *
     * @var string[]
     */
    protected $views = [
        'opes_view',
    ];
}
