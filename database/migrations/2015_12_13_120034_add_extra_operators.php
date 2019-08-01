<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Problem: The question mark operators can't be used in parameterised queries.
         * Solution: Define new operators that can be used in parameterised queries.
         *
         * Source: <http://stackoverflow.com/a/30464989/489916>
         */
        DB::statement('DROP OPERATOR IF EXISTS ~@& (jsonb, text[])');
        DB::statement('DROP OPERATOR IF EXISTS ~@| (jsonb, text[])');
        DB::statement('DROP OPERATOR IF EXISTS ~@ (jsonb, text)');

        DB::statement('CREATE OPERATOR ~@ (LEFTARG = jsonb, RIGHTARG = text, PROCEDURE = jsonb_exists)');
        DB::statement('CREATE OPERATOR ~@| (LEFTARG = jsonb, RIGHTARG = text[], PROCEDURE = jsonb_exists_any)');
        DB::statement('CREATE OPERATOR ~@& (LEFTARG = jsonb, RIGHTARG = text[], PROCEDURE = jsonb_exists_all)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP OPERATOR IF EXISTS ~@& (jsonb, text[])');
        DB::statement('DROP OPERATOR IF EXISTS ~@| (jsonb, text[])');
        DB::statement('DROP OPERATOR IF EXISTS ~@ (jsonb, text)');
    }
}
