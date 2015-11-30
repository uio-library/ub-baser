<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('author');
            $table->string('contributor');
            $table->string('translator');
            $table->string('country');
            $table->string('title');
            $table->string('title2');
            $table->string('pubYear');
            $table->string('pubPlace');
            $table->string('Genre');
            $table->string('pubYear2');
            $table->string('publisher');

            
            $table->timestamps();
            $table->softDeletes();

            $table->index('author');
            $table->index('title');

            $table->unique(['title', 'pubYear']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('letras');
    }
}
