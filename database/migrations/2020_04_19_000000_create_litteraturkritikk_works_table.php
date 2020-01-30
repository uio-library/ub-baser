<?php

use App\Bases\Litteraturkritikk\Record;
use App\Traits\MigrationHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitteraturkritikkWorksTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');

        Schema::create('litteraturkritikk_works', function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->text('verk_tittel')->nullable();
            $table->text('verk_originaltittel')->nullable();
            $table->text('verk_originaltittel_transkribert')->nullable();
            $table->text('verk_dato')->nullable();
            $table->text('verk_originaldato')->nullable();
            $table->text('verk_sjanger')->nullable();
            $table->text('verk_spraak')->nullable();
            $table->text('verk_originalspraak')->nullable();
            $table->text('verk_kommentar')->nullable();
            $table->text('verk_utgivelsessted')->nullable();
            $table->boolean('verk_forfatter_mfl')->default(false);
            $table->text('verk_fulltekst_url')->nullable();
        });

        // Kritikk av forfatterskap
        Schema::create('litteraturkritikk_subject_person', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('record_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->integer('position')->nullable();

            $table->foreign('record_id')
                ->references('id')
                ->on('litteraturkritikk_records')
                ->onDelete('cascade');

            $table->foreign('person_id')
                ->references('id')
                ->on('litteraturkritikk_personer')
                ->onDelete('cascade');
        });

        // Kritikk av verk
        Schema::create('litteraturkritikk_subject_work', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('record_id')->unsigned();
            $table->integer('work_id')->unsigned();
            $table->integer('position')->nullable();
            $table->text('edition')->nullable();

            $table->foreign('record_id')
                ->references('id')
                ->on('litteraturkritikk_records')
                ->onDelete('cascade');

            $table->foreign('work_id')
                ->references('id')
                ->on('litteraturkritikk_works')
                ->onDelete('cascade');
        });

        // Instead of renaming litteraturkritikk_record_person, we will create a new table
        // and rather delete 'litteraturkritikk_record_person' after the data has been migrated.
        Schema::create('litteraturkritikk_person_contributions', function (Blueprint $table) {

            $table->integer('person_id')->unsigned();
            $table->integer('contribution_id')->unsigned();
            $table->text('contribution_type')->default(Record::class);

            $table->text('person_role')->nullable();
            $table->text('kommentar')->nullable();
            $table->text('pseudonym')->nullable();
            $table->integer('position')->unsigned();

            $table->foreign('person_id')
                ->references('id')
                ->on('litteraturkritikk_personer')
                ->onDelete('cascade');

            $table->unique(
                ['contribution_id', 'person_id', 'person_role', 'contribution_type'],
                'litteraturkritikk_person_contributions_unique'
            );
        });

        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            $table->boolean('discusses_more')->default(false);
        });

        $this->createView('litteraturkritikk_works_view', 1);
        $this->createView('litteraturkritikk_records_search', 11);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->dropMaterializedView('litteraturkritikk_works_view');

        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            $table->dropColumn('discusses_more');
        });

        Schema::dropIfExists('litteraturkritikk_person_contributions');

        Schema::dropIfExists('litteraturkritikk_subject_person');

        Schema::dropIfExists('litteraturkritikk_subject_work');

        Schema::dropIfExists('litteraturkritikk_works');

        $this->createView('litteraturkritikk_records_search', 10);
    }
}
