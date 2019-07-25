<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDommerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dommer_kilder', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
        });

        Schema::create('dommer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
            $table->integer('aar')->unsigned();
            $table->integer('kilde_id')->unsigned();
            $table->integer('side')->unsigned();
            $table->string('note');
            $table->timestamps();
            $table->softDeletes();

            $table->index('navn');
            $table->index('aar');
            $table->index('side');

            $table->unique(['navn', 'kilde_id', 'aar', 'side']);

            $table->foreign('kilde_id')
                ->references('id')->on('dommer_kilder');
        });

        DB::unprepared("
            CREATE VIEW dommer_view AS
                SELECT
                    d.*,
                    kilder.navn as kilde_navn

                FROM dommer AS d

                JOIN dommer_kilder AS kilder
                    ON d.kilde_id = kilder.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dommer');
        Schema::drop('dommer_kilder');
    }
}
