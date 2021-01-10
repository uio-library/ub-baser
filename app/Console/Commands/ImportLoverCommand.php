<?php

namespace App\Console\Commands;

class ImportLoverCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:lover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Oversatte lover"';

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
        'oversatte_lover_lover',
        'oversatte_lover_oversettelser',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'oversatte_lover_lover.id',
        'oversatte_lover_oversettelser.id',
    ];
}
