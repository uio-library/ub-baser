<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bases', function (Blueprint $table) {
            $table->jsonb('settings')->default('{}');
        });

        \DB::table('bases')
            ->where(['id' => 'bibsys'])
            ->update([
                'settings' => json_encode(['cost_limit' => 20000]),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bases', function (Blueprint $table) {
            $table->dropColumn('settings');
        });
    }
}
