<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::create('opes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
            $table->integer('aar')->unsigned();
            $table->integer('kilde_id')->unsigned();
            $table->integer('side')->unsigned();
            $table->string('note');
            $table->timestamps();
            $table->softDeletes(); // blir ikke onterlig slettet - se paa eloquent

            $table->index('navn');
            $table->index('aar');
            $table->index('side');

            

            
        });

         /** opes_id i opes_pub sin record maa kunne ha samme verdi    **/
         Schema::create('opes_pub', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
            $table->foreign('opes_id')
                ->references('id')->on('opes');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('opes_pub');

        Schema::drop('opes');
    }
}
