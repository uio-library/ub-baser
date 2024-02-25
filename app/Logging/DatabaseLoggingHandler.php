<?php

namespace App\Logging;

use App\LogEntry;
use Illuminate\Support\Arr;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PDOException;

class DatabaseLoggingHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        try {
            LogEntry::create([
                'channel'    => $record->channel,
                'message'    => $record->message,
                'level'      => $record->level,
                'level_name' => $record->level->getName(),
                'context'    => $record->context,
                // 'extra'      => json_encode($record['extra']),
                'time'   => $record->datetime->format('Y-m-d G:i:s'),
            ]);
        } catch (PDOException $exception) {
            // Ignore, the table might not have been created yet
        }
    }
}
