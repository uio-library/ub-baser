<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitteraturkritikkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litteraturkritikk_kritikktyper', function (Blueprint $table) {
            $table->increments('id');
            $table->string('navn');
        });

        Schema::create('litteraturkritikk_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

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
//            $table->string('kritiker_etternavn')->nullable();
//            $table->string('kritiker_fornavn')->nullable();
//            $table->string('kritiker_kjonn')->nullable();
//            $table->string('kritiker_pseudonym')->nullable();
//            $table->string('kritiker_kommentar')->nullable();

            # Om det kritiserte verket og dets forfatter

//            $table->string('forfatter_etternavn')->nullable();
//            $table->string('forfatter_fornavn')->nullable();
//            $table->string('forfatter_kjonn')->nullable();
//            $table->string('forfatter_kommentar')->nullable();  # F.eks. navn på evt. medforfatter(e)), pseudonym m.m.

            $table->string('verk_tittel')->nullable();     # "Verkstittel", tittel på verket som kritiseres
            $table->string('verk_aar')->nullable();        # "Utgivelsesår", i hvilket år er verket utgitt?
            $table->string('verk_sjanger')->nullable();    # "Sjanger", hvilken sjanger tilhører verket?
            $table->jsonb('verk_spraak')->nullable();
            $table->string('verk_kommentar')->nullable();  # F.eks. undertitler, forlag, opplag, omarbeidet utgave m.m.
            $table->string('verk_utgivelsessted')->nullable();

//            $table->index('forfatter_etternavn');
//            $table->index('forfatter_fornavn');
            $table->index('verk_tittel');
//            $table->index('kritiker_etternavn');
//            $table->index('kritiker_fornavn');
//            $table->index('kritiker_pseudonym');
            $table->index('publikasjon');

            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();

            $table->boolean('kritiker_mfl')->default(false);
            $table->boolean('forfatter_mfl')->default(false);
            $table->string('kritiker_kommentar')->nullable();
            $table->string('forfatter_kommentar')->nullable();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

        Schema::create('litteraturkritikk_personer', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('etternavn')->nullable();
            $table->string('fornavn')->nullable();
            $table->string('pseudonym_for')->nullable();
            $table->string('pseudonym')->nullable();
            $table->string('kommentar')->nullable();
            $table->string('kjonn', 1)->nullable();
            $table->string('bibsys_id')->nullable();
            $table->tinyInteger('birth_year', false, true)->nullable();
            $table->tinyInteger('death_year', false, true)->nullable();
            $table->index('fornavn');
            $table->unique(['etternavn', 'fornavn', 'birth_year']);
        });

        Schema::create('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('record_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->string('person_role')->nullable();
            $table->string('kommentar')->nullable();  # F.eks. navn på evt. medforfatter(e)), pseudonym m.m.
            $table->string('pseudonym')->nullable();
        });

        DB::unprepared("
            CREATE MATERIALIZED VIEW litteraturkritikk_records_search AS
                SELECT
                    litteraturkritikk_records.*,

                    substr(trim(aar),1,4) AS aar_numeric,

                    to_tsvector('simple', coalesce(litteraturkritikk_records.tittel, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.verk_tittel, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.kommentar, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.utgivelseskommentar, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.verk_kommentar, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.forfatter_kommentar, ''))
                    || to_tsvector('simple', coalesce(litteraturkritikk_records.kritiker_kommentar, ''))
                    || to_tsvector('simple', coalesce(string_agg(litteraturkritikk_personer.etternavn, ' '), ''))
                    || to_tsvector('simple', coalesce(string_agg(litteraturkritikk_personer.fornavn, ' '), ''))
                    || to_tsvector('simple', coalesce(string_agg(litteraturkritikk_personer.pseudonym, ' '), ''))
                    || to_tsvector('simple', coalesce(string_agg(litteraturkritikk_personer.pseudonym_for, ' '), ''))
                    AS document

                FROM litteraturkritikk_records
                    LEFT JOIN litteraturkritikk_record_person
                        ON litteraturkritikk_records.id=litteraturkritikk_record_person.record_id
                    LEFT JOIN litteraturkritikk_personer
                        ON litteraturkritikk_personer.id=litteraturkritikk_record_person.person_id

                GROUP BY litteraturkritikk_records.id
        ");

        DB::unprepared('CREATE INDEX litteraturkritikk_fts_search ON litteraturkritikk_records_search USING gin(document)');

        DB::unprepared("
            CREATE VIEW litteraturkritikk_personer_view AS
                SELECT
                    litteraturkritikk_personer.*,
                    (CASE
                        WHEN litteraturkritikk_personer.fornavn IS NOT NULL AND litteraturkritikk_personer.etternavn IS NOT NULL
                        THEN litteraturkritikk_personer.fornavn::text || ' '::text || litteraturkritikk_personer.etternavn::text
                        WHEN litteraturkritikk_personer.fornavn IS NOT NULL
                        THEN litteraturkritikk_personer.fornavn
                        ELSE ''
                    END) AS fornavn_etternavn,
                    (CASE
                        WHEN litteraturkritikk_personer.fornavn IS NOT NULL AND litteraturkritikk_personer.etternavn IS NOT NULL
                        THEN litteraturkritikk_personer.etternavn::text || ', '::text || litteraturkritikk_personer.fornavn::text
                        WHEN litteraturkritikk_personer.etternavn IS NOT NULL
                        THEN litteraturkritikk_personer.etternavn
                        ELSE ''
                    END) AS etternavn_fornavn
                FROM litteraturkritikk_personer;
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP MATERIALIZED VIEW litteraturkritikk_records_search');
        DB::unprepared('DROP VIEW litteraturkritikk_personer_view');

        Schema::drop('litteraturkritikk_record_person');
        Schema::drop('litteraturkritikk_records');
        Schema::drop('litteraturkritikk_personer');
        Schema::drop('litteraturkritikk_kritikktyper');
    }
}
