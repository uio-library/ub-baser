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
            $table->text('navn');
        });

        Schema::create('litteraturkritikk_records', function (Blueprint $table) {

            # ------------------------------------------------------------------------------------------------
            # Meta

            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            # ------------------------------------------------------------------------------------------------
            # Kritikken

            $table->jsonb('kritikktype');
            $table->text('spraak')->nullable();
            $table->text('tittel')->nullable();
            $table->text('publikasjon')->nullable();
            $table->text('utgivelsessted')->nullable();
            $table->text('aar')->nullable();
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

            # ------------------------------------------------------------------------------------------------
            # Det kritiserte verket

            $table->text('verk_tittel')->nullable();
            $table->text('verk_aar')->nullable();
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
            $table->text('kommentar')->nullable();
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
            $table->text('kommentar')->nullable();   // Foreløpig ikke i bruk, men innholdet fra verk_forfatter_kommentar og kritiker_kommentar kunne vært flyttet hit.
            $table->text('pseudonym')->nullable();   // Foreløpig ikke i bruk, Innholdet fra verk_forfatter_pseudonym kunne vært flyttet hit.

            $table->foreign('record_id')
                ->references('id')
                ->on('litteraturkritikk_records')
                ->onDelete('cascade');

            $table->foreign('person_id')
                ->references('id')
                ->on('litteraturkritikk_personer')
                ->onDelete('cascade');
        });

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

        DB::unprepared("
            CREATE MATERIALIZED VIEW litteraturkritikk_records_search AS
            SELECT
        
                litteraturkritikk_records.*,
                                    
                SUBSTR(TRIM(aar),1,4) AS aar_numeric,
                
                -- Flat representasjon for tabellvisning
                STRING_AGG(DISTINCT forfatter_entity.etternavn_fornavn, '; ') AS verk_forfatter,
                STRING_AGG(DISTINCT kritiker_entity.etternavn_fornavn, '; ') AS kritiker,
                
                -- Søkeindeks 'any_field_ts'
                TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.tittel, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.publikasjon, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.aar, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.bind, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.hefte, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.sidetall, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.kommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.utgivelseskommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.verk_tittel, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.verk_aar, ''))
                || TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.verk_kommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(lk_person_pivot.kommentar, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(lk_person_pivot.pseudonym, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_entity.etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_entity.fornavn, ' '), ''))
                AS any_field_ts,
                
                -- Søkeindeks 'verk_tittel_ts'
                TO_TSVECTOR('simple', COALESCE(litteraturkritikk_records.verk_tittel, ''))
                AS verk_tittel_ts,
                
                -- Søkeindeks 'forfatter_ts'
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter_entity.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter_entity.fornavn_etternavn, ' '), ''))
                AS forfatter_ts,

                -- Søkeindeks 'kritiker_ts'
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker_entity.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker_entity.fornavn_etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT lk_kritiker_pivot.pseudonym, ' '), ''))
                AS kritiker_ts,

                -- Søkeindeks 'person_ts'
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person_entity.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person_entity.fornavn_etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT lk_person_pivot.pseudonym, ' '), ''))
                AS person_ts
        
            FROM litteraturkritikk_records
            
            -- person
            LEFT JOIN litteraturkritikk_record_person AS lk_person_pivot
                ON litteraturkritikk_records.id = lk_person_pivot.record_id
        
                LEFT JOIN litteraturkritikk_personer_view AS person_entity
                    ON person_entity.id = lk_person_pivot.person_id
        
            -- kritiker
            LEFT JOIN litteraturkritikk_record_person AS lk_kritiker_pivot
                ON litteraturkritikk_records.id = lk_kritiker_pivot.record_id
                AND lk_kritiker_pivot.person_role = 'kritiker'
        
                LEFT JOIN litteraturkritikk_personer_view AS kritiker_entity
                    ON kritiker_entity.id = lk_kritiker_pivot.person_id
        
            -- forfatter
            LEFT JOIN litteraturkritikk_record_person AS lk_forfatter_pivot
                ON lk_forfatter_pivot.record_id = litteraturkritikk_records.id
                AND lk_forfatter_pivot.person_role != 'kritiker'
        
                LEFT JOIN litteraturkritikk_personer_view AS forfatter_entity
                    ON forfatter_entity.id = lk_forfatter_pivot.person_id
        
            GROUP BY litteraturkritikk_records.id
        ");

        DB::unprepared('CREATE UNIQUE INDEX litteraturkritikk_records_search_id ON litteraturkritikk_records_search (id)');
        DB::unprepared('CREATE INDEX litteraturkritikk_any_field_ts_idx ON litteraturkritikk_records_search USING gin(any_field_ts)');
        DB::unprepared('CREATE INDEX litteraturkritikk_forfatter_ts_idx ON litteraturkritikk_records_search USING gin(forfatter_ts)');
        DB::unprepared('CREATE INDEX litteraturkritikk_kritiker_ts_idx ON litteraturkritikk_records_search USING gin(kritiker_ts)');
        DB::unprepared('CREATE INDEX litteraturkritikk_person_ts_idx ON litteraturkritikk_records_search USING gin(person_ts)');

        Schema::table('litteraturkritikk_records_search', function($view) {
            $view->index('publikasjon');
            $view->index('verk_sjanger');
            $view->index('kritikktype');
            $view->index('spraak');
        });
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
