<?php

use App\Traits\MigrationHelper;
use Carbon\Carbon;
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
        // Vi hadde tidligere en ufullstendig migration "2015_10_17_151207_create_opes_table.php"
        // som egentlig ikke burde vært committa. På grunn av denne tar vi en DROP her:
        Schema::dropIfExists('opes_pub');
        Schema::dropIfExists('opes');

        Schema::create('opes', function (Blueprint $table) {
            $this->addCommonFields($table, true);

			$table->text('inv_no');
			$table->text('section_or_side')->nullable();
			$table->text('material')->nullable();
			$table->text('connections')->nullable();
			$table->text('rep_ser_old')->nullable();
			$table->text('rep_pg_no_old')->nullable();
			$table->text('mounted')->nullable();
			$table->text('negative')->nullable();
			$table->text('acquisition')->nullable();
			$table->text('title_or_type')->nullable();
			$table->text('size')->nullable();
			$table->text('notes_on_preservation')->nullable();
			$table->text('provenance')->nullable();
			// $table->text('language')->nullable(); Kan avledes fra language_code
			$table->text('lines')->nullable();
			$table->text('palaeographic_description')->nullable();
			$table->text('further_rep')->nullable();
			$table->text('author')->nullable();
			$table->text('content')->nullable();

			$table->jsonb('persons')->default('[]');

			$table->text('geographica')->nullable();
			$table->text('extent')->nullable();
			$table->text('institution')->nullable();
			$table->text('origin')->nullable();
			$table->text('conservation_status')->nullable();

			$table->integer('items')->unsigned()->nullable();

			$table->text('publ_side')->nullable();
			$table->text('genre')->nullable();
			$table->text('fullsizefront_r1')->nullable();
			$table->text('fullsizeback_r1')->nullable();
			$table->text('translation')->nullable();
			$table->text('status')->nullable();
			$table->text('bibliography')->nullable();

			$table->boolean('negative_in_copenhagen')->nullable();
			$table->date('date_cataloged')->nullable();

			$table->text('processing_number')->nullable();

            // Hva er dette?
			$table->integer('quote')->unsigned()->nullable();

			$table->text('further_replication_note')->nullable();
			$table->text('extent_genre')->nullable();
			$table->string('language_code', 3)->nullable();

            // OBS: Disse inneholder også strenger, men ser ut som de er MENT å være tall
			$table->string('date1')->nullable(); /*_data range for serching low */
			$table->string('date2')->nullable(); /*_data range for serching high*/

			$table->text('date')->nullable(); /*_exampel_132/133_a.d.*/

			$table->text('title_statement')->nullable();
			$table->text('material_long')->nullable();  // OBS: VI har BÅDE material og material_long. Kan de ha noen annen verdi enn "Papyrus" i en papyrussamling???
			$table->jsonb('subj_headings')->default('[]');
        });

        Schema::create('opes_publications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('opes_id')->unsigned();
            $table->foreign('opes_id')
                ->references('id')->on('opes');

            $table->text('ser_vol')->nullable();
            $table->text('editor')->nullable();
            $table->text('year')->nullable();
            $table->text('pg_no')->nullable();
            $table->text('photo')->nullable();
            $table->text('sb')->nullable();
            $table->text('corrections')->nullable();
            $table->text('preferred_citation')->nullable();
            $table->text('ddbdp_pmichcitation')->nullable();
            $table->text('ddbdp_omichcitation')->nullable();
            $table->text('ddbdp_p_rep')->nullable();
            $table->text('ddbdp_o_rep')->nullable();
        });

        $this->createView('opes_view', 1);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('opes_view');

        Schema::drop('opes_publications');
        Schema::drop('opes');
    }
}
