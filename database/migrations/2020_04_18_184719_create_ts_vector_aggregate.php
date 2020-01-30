<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsVectorAggregate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::raw("
            create aggregate tsvector_agg (tsvector) (
                STYPE = pg_catalog.tsvector,
                SFUNC = pg_catalog.tsvector_concat,
                INITCOND = ''
            )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::raw("drop aggregate tsvector_agg");
    }
}
