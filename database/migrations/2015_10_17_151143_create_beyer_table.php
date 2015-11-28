<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeyerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beyer_kritikktyper', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
        });

        Schema::create('beyer', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            # Om kritikken
            $table->jsonb('kritikktype');
            $table->string('kommentar')->nullable();  # Om innholdet i kritikken. Hvorvidt publisert flere steder, f.eks. ”jfr.” eller ”også”.
            $table->string('spraak')->nullable();     # Språk, kritikk

            # Om utgivelsen av kritikken
            $table->string('tittel')->nullable();
            $table->text('publikasjon')->nullable();   # > 250 chars
            $table->string('utgivelsessted')->nullable();
            $table->string('aar')->nullable();
            $table->string('dato')->nullable();
            $table->string('aargang')->nullable();
            $table->string('nummer')->nullable();
            $table->string('bind')->nullable();
            $table->string('hefte')->nullable();
            $table->string('sidetall')->nullable();
            $table->string('utgivelseskommentar')->nullable();   # Eventuelle tilleggsopplysninger om publiseringen. F.eks. ”egenpublisert”

            # Om kritikeren
            $table->string('kritiker_etternavn')->nullable();
            $table->string('kritiker_fornavn')->nullable();
            $table->string('kritiker_kjonn')->nullable();
            $table->string('kritiker_pseudonym')->nullable();
            $table->string('kritiker_kommentar')->nullable();

            # Om det kritiserte verket og dets forfatter
            $table->string('forfatter_etternavn')->nullable();
            $table->string('forfatter_fornavn')->nullable();
            $table->string('forfatter_kjonn')->nullable();
            $table->string('forfatter_kommentar')->nullable();  # F.eks. navn på evt. medforfatter(e)), pseudonym m.m.
            $table->string('verk_tittel')->nullable();     # "Verkstittel", tittel på verket som kritiseres
            $table->string('verk_aar')->nullable();        # "Utgivelsesår", i hvilket år er verket utgitt?
            $table->string('verk_sjanger')->nullable();    # "Sjanger", hvilken sjanger tilhører verket?
            $table->jsonb('verk_spraak')->nullable();
            $table->string('verk_kommentar')->nullable();  # F.eks. undertitler, forlag, opplag, omarbeidet utgave m.m.
            $table->string('verk_utgivelsessted')->nullable();

            $table->index('forfatter_etternavn');
            $table->index('forfatter_fornavn');
            $table->index('verk_tittel');
            $table->index('kritiker_etternavn');
            $table->index('kritiker_fornavn');
            $table->index('kritiker_pseudonym');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('beyer');
        Schema::drop('beyer_kritikktyper');
    }
}
