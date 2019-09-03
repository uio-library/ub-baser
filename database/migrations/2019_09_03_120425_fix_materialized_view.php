<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMaterializedView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            DB::unprepared('CREATE UNIQUE INDEX litteraturkritikk_records_search_id ON litteraturkritikk_records_search (id)');
            DB::unprepared('CREATE INDEX litteraturkritikk_any_field_ts_idx ON litteraturkritikk_records_search USING gin(any_field_ts)');
            DB::unprepared('CREATE INDEX litteraturkritikk_forfatter_ts_idx ON litteraturkritikk_records_search USING gin(forfatter_ts)');
            DB::unprepared('CREATE INDEX litteraturkritikk_kritiker_ts_idx ON litteraturkritikk_records_search USING gin(kritiker_ts)');
            DB::unprepared('CREATE INDEX litteraturkritikk_person_ts_idx ON litteraturkritikk_records_search USING gin(person_ts)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            //
        });
    }
}
