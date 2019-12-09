<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel');
            $table->text('message');
            $table->string('level');
            $table->string('level_name');
            $table->jsonb('context');
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->dateTime('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_entries');
    }
}
