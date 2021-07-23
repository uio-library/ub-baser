<?php

namespace App\Console\Commands;

class ImportNordskriftCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:nordskrift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Nordisk skriftkultur bibliografi" (NSB)';

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
        'nordskrift',
    ];

    /**
     * Sequences to update
     *
     * @var string[]
     */
    protected $sequences = [
        'nordskrift.id',
    ];
}
