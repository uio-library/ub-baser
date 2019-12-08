<?php

namespace App\Logging;

use App\LogEntry;
use Illuminate\Support\Arr;
use Monolog\Handler\AbstractProcessingHandler;
use PDOException;

class DatabaseLoggingHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        try {
            LogEntry::create([
                'channel'    => Arr::get($record, 'channel'),
                'message'    => Arr::get($record, 'message'),
                'level'      => Arr::get($record, 'level'),
                'level_name' => Arr::get($record, 'level_name'),
                'context'    => $record['context'],
                // 'extra'      => json_encode($record['extra']),
                'time'   => $record['datetime']->format('Y-m-d G:i:s'),
            ]);
        } catch (PDOException $exception) {
            // Ignore, the table might not have been created yet
        }
    }
}
