<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function assertDatabaseIsSane()
    {
        $res = \DB::select('SHOW LC_COLLATE');
        $lc_collate = $res[0]->lc_collate;
        if (!in_array($lc_collate, ['no_NO.UTF-8', 'nb_NO.UTF-8'])) {
            throw new \ErrorException('Database has unsupported collation: ' . $lc_collate . '. Supported collations are: nb_NO.UTF-8, no_NO.UTF-8.');
        }

        $res = \DB::select('SHOW LC_CTYPE');
        $lc_ctype = $res[0]->lc_ctype;
        if (!in_array($lc_ctype, ['no_NO.UTF-8', 'nb_NO.UTF-8'])) {
            throw new \ErrorException('Database has unsupported ctype: ' . $lc_ctype . '. Supported ctypes are: nb_NO.UTF-8, no_NO.UTF-8.');
        }

        if ($lc_collate != $lc_ctype) {
            throw new \ErrorException('Database ctype ' . $lc_ctype . ' differs from collation ' . $lc_collate . '.');
        }
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        /**
         * Since this is the first migration to run, let's first check
         * if the database config is sane.
         */
        $this->assertDatabaseIsSane();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->jsonb('rights');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
