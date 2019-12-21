<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLitteraturkritikkTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litteraturkritikk_kritikktyper', function (Blueprint $table) {
            $table->increments('id');
            $table->text('navn');
        });

        Schema::create('litteraturkritikk_records', function (Blueprint $table) {
            $this->addCommonFields($table);

            // ------------------------------------------------------------------------------------------------
            // Kritikken

            $table->jsonb('kritikktype');
            $table->text('spraak')->nullable();
            $table->text('tittel')->nullable();
            $table->text('publikasjon')->nullable();
            $table->text('utgivelsessted')->nullable();
            $table->text('dato')->nullable();
            $table->text('aargang')->nullable();
            $table->text('nummer')->nullable();
            $table->text('bind')->nullable();
            $table->text('hefte')->nullable();
            $table->text('sidetall')->nullable();

            $table->text('utgivelseskommentar')->nullable();
            $table->text('kommentar')->nullable();

            $table->boolean('kritiker_mfl')->default(false);

            $table->text('fulltekst_url')->nullable();

            // ------------------------------------------------------------------------------------------------
            // Det kritiserte verket

            $table->text('verk_tittel')->nullable();
            $table->text('verk_dato')->nullable();
            $table->text('verk_sjanger')->nullable();
            $table->text('verk_spraak')->nullable();
            $table->text('verk_kommentar')->nullable();
            $table->text('verk_utgivelsessted')->nullable();

            $table->boolean('verk_forfatter_mfl')->default(false);
        });

        Schema::create('litteraturkritikk_personer', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->text('etternavn')->nullable();
            $table->text('fornavn')->nullable();
            $table->text('kjonn', 1)->nullable();
            $table->tinyInteger('fodt', false, true)->nullable();
            $table->tinyInteger('dod', false, true)->nullable();
            $table->text('bibsys_id')->nullable();
            $table->text('wikidata_id')->nullable();

            $table->index('fornavn');
            $table->unique(['etternavn', 'fornavn', 'fodt']);
        });

        Schema::create('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('record_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->text('person_role')->nullable();
            $table->text('kommentar')->nullable();
            $table->text('pseudonym')->nullable();

            $table->foreign('record_id')
                ->references('id')
                ->on('litteraturkritikk_records')
                ->onDelete('cascade');

            $table->foreign('person_id')
                ->references('id')
                ->on('litteraturkritikk_personer')
                ->onDelete('cascade');
        });

        $this->createView('litteraturkritikk_personer_view', 1);
        $this->createView('litteraturkritikk_records_search', 1);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->dropView('litteraturkritikk_personer_view');

        Schema::drop('litteraturkritikk_record_person');
        Schema::drop('litteraturkritikk_records');
        Schema::drop('litteraturkritikk_personer');
        Schema::drop('litteraturkritikk_kritikktyper');
    }
}
