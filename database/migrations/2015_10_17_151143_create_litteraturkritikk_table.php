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
                    p.*,

                    -- Fullt navn med etternavn først (invertert)
                    (CASE
                        -- Etternavn, Fornavn (Født-Død)
                        WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL AND p.fodt IS NOT NULL AND p.dod IS NOT NULL
                        THEN p.etternavn::text || ', '::text || p.fornavn::text || ', '::text || p.fodt::text || '-'::text || p.dod::text

                        -- Etternavn, Fornavn (Født-)
                        WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL AND p.fodt IS NOT NULL
                        THEN p.etternavn::text || ', '::text || p.fornavn::text || ', '::text || p.fodt::text || '-'::text

                        -- Etternavn, Fornavn
                        WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL
                        THEN p.etternavn::text || ', '::text || p.fornavn::text

                        -- Etternavn
                        WHEN p.etternavn IS NOT NULL
                        THEN p.etternavn

                        ELSE ''
                    END) AS etternavn_fornavn,

                    -- Fullt navn med fornavn først, uten dato
                    (CASE
                        -- Etternavn, Fornavn
                        WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL
                        THEN p.fornavn::text || ' '::text || p.etternavn::text

                        -- Etternavn
                        WHEN p.etternavn IS NOT NULL
                        THEN p.etternavn

                        ELSE ''
                    END) AS fornavn_etternavn,

                    -- Roller
                    ARRAY_AGG(DISTINCT pivot.person_role)
                    AS roller,

                    -- Søkeindeks 'any_field_ts'. 
                    -- Vi legger til navn begge veier for å støtte autocomplete med frasematch begge veier.
                    TO_TSVECTOR('simple', COALESCE(p.etternavn, ''))
                    || TO_TSVECTOR('simple', COALESCE(p.fornavn, ''))
                    || TO_TSVECTOR('simple', COALESCE(p.fodt::text, ''))
                    || TO_TSVECTOR('simple', COALESCE(p.dod::text, ''))
                    || TO_TSVECTOR('simple', COALESCE(p.fornavn, ''))
                    || TO_TSVECTOR('simple', COALESCE(p.etternavn, ''))
                    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(pivot.pseudonym, ' '), ''))
                    AS any_field_ts

                FROM litteraturkritikk_personer AS p

                -- person
                LEFT JOIN litteraturkritikk_record_person AS pivot
                    ON p.id = pivot.person_id
                                

                GROUP BY p.id
        ");

        DB::unprepared("
            CREATE MATERIALIZED VIEW litteraturkritikk_records_search AS
            SELECT
        
                r.*,
                                    
                SUBSTR(TRIM(dato),1,4) AS dato_numeric,
                
                -- Flat representasjon for tabellvisning
                STRING_AGG(DISTINCT forfatter.etternavn_fornavn, '; ') AS verk_forfatter,
                STRING_AGG(DISTINCT kritiker.etternavn_fornavn, '; ') AS kritiker,
                
                -- Søkeindeks 'any_field_ts'
                TO_TSVECTOR('simple', COALESCE(r.tittel, ''))
                || TO_TSVECTOR('simple', COALESCE(r.publikasjon, ''))
                || TO_TSVECTOR('simple', COALESCE(r.dato, ''))
                || TO_TSVECTOR('simple', COALESCE(r.bind, ''))
                || TO_TSVECTOR('simple', COALESCE(r.hefte, ''))
                || TO_TSVECTOR('simple', COALESCE(r.sidetall, ''))
                || TO_TSVECTOR('simple', COALESCE(r.kommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(r.utgivelseskommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(r.verk_tittel, ''))
                || TO_TSVECTOR('simple', COALESCE(r.verk_dato, ''))
                || TO_TSVECTOR('simple', COALESCE(r.verk_kommentar, ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_pivot.kommentar, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_pivot.pseudonym, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.fornavn, ' '), ''))
                AS any_field_ts,
                
                -- Søkeindeks 'verk_tittel_ts'
                TO_TSVECTOR('simple', COALESCE(r.verk_tittel, ''))
                AS verk_tittel_ts,
                
                -- Søkeindeks 'forfatter_ts'. 
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter.fornavn_etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter_pivot.pseudonym, ' '), ''))
                AS forfatter_ts,

                -- Søkeindeks 'kritiker_ts'
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker.fornavn_etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker_pivot.pseudonym, ' '), ''))
                AS kritiker_ts,

                -- Søkeindeks 'person_ts'
                TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person.etternavn_fornavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person.fornavn_etternavn, ' '), ''))
                || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person_pivot.pseudonym, ' '), ''))
                AS person_ts
        
            FROM litteraturkritikk_records AS r
            
            -- person
            LEFT JOIN litteraturkritikk_record_person AS person_pivot
                ON r.id = person_pivot.record_id
        
                LEFT JOIN litteraturkritikk_personer_view AS person
                    ON person.id = person_pivot.person_id
        
            -- kritiker
            LEFT JOIN litteraturkritikk_record_person AS kritiker_pivot
                ON r.id = kritiker_pivot.record_id
                AND kritiker_pivot.person_role = 'kritiker'
        
                LEFT JOIN litteraturkritikk_personer_view AS kritiker
                    ON kritiker.id = kritiker_pivot.person_id
        
            -- forfatter
            LEFT JOIN litteraturkritikk_record_person AS forfatter_pivot
                ON forfatter_pivot.record_id = r.id
                AND forfatter_pivot.person_role != 'kritiker'
        
                LEFT JOIN litteraturkritikk_personer_view AS forfatter
                    ON forfatter.id = forfatter_pivot.person_id
        
            GROUP BY r.id
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
