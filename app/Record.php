<?php

namespace App;

abstract class Record extends \Eloquent
{
    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    abstract public function getTitle(): string;

    public function nextRecord(): int
    {
        $rec = self::where('id', '>', $this->id)
            ->orderBy('id', 'asc')
            ->limit(1)
            ->select('id')
            ->first();
        if (is_null($rec)) {
            $rec = self::orderBy('id', 'asc')
                ->select('id')
                ->first();
        }

        return $rec->id;
    }

    public function prevRecord(): int
    {
        $rec = self::where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->first();
        if (is_null($rec)) {
            $rec = self::orderBy('id', 'desc')
                ->select('id')
                ->first();
        }

        return $rec->id;
    }
}
