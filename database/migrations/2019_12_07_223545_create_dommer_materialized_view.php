<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDommerMaterializedView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            DROP VIEW dommer_view
        ");

        DB::unprepared("
            CREATE MATERIALIZED VIEW dommer_view AS
                SELECT
                    d.*,
                    kilder.navn as kilde_navn

                FROM dommer AS d

                JOIN dommer_kilder AS kilder
                    ON d.kilde_id = kilder.id
        ");

        DB::unprepared('CREATE UNIQUE INDEX dommer_view_id ON dommer_view (id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("
            DROP MATERIALIZED VIEW dommer_view
        ");

        DB::unprepared("
            CREATE VIEW dommer_view AS
                SELECT
                    d.*,
                    kilder.navn as kilde_navn

                FROM dommer AS d

                JOIN dommer_kilder AS kilder
                    ON d.kilde_id = kilder.id
        ");
    }
}
