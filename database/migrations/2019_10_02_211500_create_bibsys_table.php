<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibsysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibsys', function (Blueprint $table) {
            $table->text('objektid')->unique();
            $table->text('title_statement')->nullable();
            $table->text('pub_date')->nullable();
            $table->text('marc_record');
            $table->text('marc_record_text');
        });

        Schema::create('bibsys_dok', function (Blueprint $table) {
            $table->text('dokid')->unique();

            $table->text('objektid');
            $table->text('strekkode')->nullable();
            $table->text('seriedokid')->nullable();

            $table->text('status')->nullable();
            $table->text('statusdato')->nullable();

            $table->text('avdeling')->nullable();
            $table->text('samling')->nullable();
            $table->text('hyllesignatur')->nullable();

            $table->text('deponert')->nullable();
            $table->text('lokal_anmerkning')->nullable();
            $table->text('beholdning')->nullable();
            $table->text('utlaanstype')->nullable();
            $table->text('lisensbetingelser')->nullable();
            $table->text('tilleggsplassering')->nullable();
            $table->text('intern_bemerkning_aapen')->nullable();
            $table->text('bestillingstype')->nullable();

            $table->boolean('har_hefter')->default('0');
        });

        DB::unprepared("
            CREATE MATERIALIZED VIEW bibsys_search AS
            SELECT
                d.*,
                b.title_statement,
                b.pub_date,
                b.marc_record,
                b.marc_record_text,

                -- Søkeindeks 'any_field_ts'
                TO_TSVECTOR('simple', COALESCE(b.objektid, ''))
                || TO_TSVECTOR('simple', COALESCE(d.dokid, ''))
                || TO_TSVECTOR('simple', COALESCE(d.seriedokid, ''))
                || TO_TSVECTOR('simple', COALESCE(d.strekkode, ''))
                || TO_TSVECTOR('simple', COALESCE(d.avdeling, ''))
                || TO_TSVECTOR('simple', COALESCE(d.samling, ''))
                || TO_TSVECTOR('simple', COALESCE(d.hyllesignatur, ''))
                || TO_TSVECTOR('simple', COALESCE(b.marc_record_text, ''))
                AS any_field_ts

            FROM bibsys AS b

            JOIN bibsys_dok AS d
                ON b.objektid = d.objektid
        ");

        // Create indices for use exact queries combined with ORDER BY dokid
        foreach (['dokid', 'objektid', 'seriedokid', 'strekkode'] as $field) {
            DB::unprepared("
                CREATE INDEX bibsys_search_${field}_idx ON bibsys_search(${field}, dokid)
            ");
        }

        // Create indices for LIKE queries: {field} ~~ lower(?*), combined with ORDER BY dokid
        foreach (['avdeling', 'samling', 'hyllesignatur'] as $field) {
            DB::unprepared("
                CREATE INDEX bibsys_search_${field}_idx ON bibsys_search(lower(${field}) text_pattern_ops, dokid)
            ");
        }

        // Create GIN index for any_field_ts
        DB::unprepared("
            CREATE INDEX bibsys_search_any_field_idx ON bibsys_search USING GIN (any_field_ts)
        ");

        // DB::unprepared("
        //     CREATE INDEX bibsys_search_samling_idx ON bibsys_search USING gin (samling gin_trgm_ops);
        // ");

        // DB::unprepared("
        //     CREATE INDEX bibsys_search_pub_date_idx ON bibsys_search USING btree(pub_date);
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP MATERIALIZED VIEW bibsys_search');
        Schema::dropIfExists('bibsys_dok');
        Schema::dropIfExists('bibsys');
    }
}
