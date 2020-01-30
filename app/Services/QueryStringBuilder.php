<?php

namespace App\Services;

use App\Schema\Schema;

class QueryStringBuilder
{
    /**
     * @var Schema
     */
    protected $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function build($stmts)
    {
        $out = '';
        foreach ($stmts as $stmt) {
            foreach ($stmt as $part) {
                // In the future, we could validate against schema
                $out .= ' ' . $part ;
            }
        }
        return trim($out);
    }
}
