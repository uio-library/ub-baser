<?php

use Illuminate\Database\Migrations\Migration;

class OptimizeBibsysIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create indices for use exact queries
        foreach (['dokid', 'objektid', 'seriedokid', 'strekkode'] as $field) {
            DB::unprepared("
                DROP INDEX bibsys_search_${field}_idx
            ");
            DB::unprepared("
                CREATE INDEX bibsys_search_${field}_idx ON bibsys_search(${field})
            ");
        }

        // Create indices for LIKE queries: {field} ~~ lower(?*)
        // Since we are targeting the C locale, we don't need text_pattern_ops .
        foreach (['avdeling', 'samling', 'hyllesignatur'] as $field) {
            DB::unprepared("
                DROP INDEX bibsys_search_${field}_idx
            ");
            DB::unprepared("
                CREATE INDEX bibsys_search_${field}_idx ON bibsys_search(lower(${field}))
            ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
