<?php

namespace App\Console\Commands;

class ImportLetrasCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:letras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Letras"';

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
        'letras',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'letras.id',
    ];
}
