<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversatte_lover', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date('dato');
            $table->integer('nummer')->unsigned();
            $table->enum('dok_type', ['lov', 'forskrift']);
            $table->text('tittel');
            $table->text('kort_tittel')->nullable();
            $table->text('note')->nullable();

            $table->unique(['dato', 'nummer', 'dok_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oversatte_lover');
    }
}
