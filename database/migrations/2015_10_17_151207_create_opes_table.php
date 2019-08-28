<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOpesTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opes', function (Blueprint $table) {

            $this->addCommonFields($table);

            $table->string('navn');
            $table->integer('aar')->unsigned();
            $table->integer('kilde_id')->unsigned();
            $table->integer('side')->unsigned();
            $table->string('note');

            $table->index('navn');
            $table->index('aar');
            $table->index('side');
        });

         /** opes_id i opes_pub sin record maa kunne ha samme verdi    **/
         Schema::create('opes_pub', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('opes_id')->unsigned();
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
