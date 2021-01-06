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
            $table->softDeletes();
            $table->integer('lov_id')->unsigned();
            $table->text('sprak');
            $table->text('tittel');
            $table->text('kort_tittel')->nullable();
            $table->text('oversetter')->nullable();
            $table->text('bibsys')->nullable();
            $table->text('url')->nullable();
            $table->text('note')->nullable();
            $table->text('inote')->nullable();
            $table->text('utgave')->nullable();

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
