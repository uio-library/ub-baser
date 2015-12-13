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
        Schema::dropIfExists('letras');
        Schema::create('letras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('forfatter');
            $table->string('land');
            $table->string('tittel');
            $table->string('utgivelsesaar');
            $table->string('sjanger');
            $table->string('oversetter');
            $table->string('tittel2');
            $table->string('utgivelsessted');
            $table->string('utgivelsesaar2');
            $table->string('forlag');
            $table->string('foretterord');
            $table->string('spraak');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('forfatter');
            $table->index('tittel');
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
