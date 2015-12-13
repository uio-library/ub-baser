<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $fields = [];

    public function mapToFields($data)
    {
        $nRecords = count($data);
        $nFields = count($this->fields);

        if ($nFields != count($data[0])) {
            throw new \ErrorException('Expected ' . $nFields . ' fields, got ' . count($data[0]) . ' fields.');
        }

        for ($i = 0; $i < $nRecords; $i++) {
            $row = [];
            for ($j = 0; $j < $nFields; $j++) {
                $row[$this->fields[$j]] = $data[$i][$j];
            }
            $data[$i] = $row;
        }
        return $data;
    }
}
