<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOversettelserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversettelser', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('lov_id')->unsigned();
            $table->date('dato');
            $table->string('sprak');
            $table->string('tittel');
            $table->string('kort_tittel')->nullable();
            $table->string('oversetter')->nullable();
            $table->string('bibsys')->nullable();
            $table->string('url')->nullable();
            $table->string('note')->nullable();
            $table->string('inote')->nullable();
            $table->string('utgave')->nullable();

            $table->foreign('lov_id')
                ->references('id')->on('oversatte_lover');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oversettelser');
    }
}
