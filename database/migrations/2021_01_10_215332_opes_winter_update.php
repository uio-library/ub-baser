<?php

use App\Bases\Opes\Record;
use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OpesWinterUpdate extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dropMaterializedView('opes_view');

        Schema::table('opes', function (Blueprint $table) {

            // 1.1 / 1.2
            $table->text('p_oslo_vol')->nullable();
            $table->text('p_oslo_nr')->nullable();

            // 1.4
            $table->text('internal_comments')->nullable();

            // 1.5
            $table->text('conservation_notes')->nullable();

            // 1.8
            $table->smallInteger('acquisition_year')->unsigned()->nullable();

            // 1.6
            $table->text('trismegistos_url')->nullable();

            // 1.7
            $table->text('papyri_dclp_url')->nullable();

            // 4.6
            $table->renameColumn('subj_headings', 'subjects');

            // 4.7
            $table->renameColumn('persons', 'people');

            // 4.9
            $table->renameColumn('geographica', 'places');
        });

        // Change columns to JSON arrays

        // Wait with this since we don't have a UI element yet
        // $this->changeColumnToJsonArray('opes', 'bibliography');

        // 4.10
        $this->changeColumnToJsonArray('opes', 'places');

        // Fix empty arrays for people: [""] -> []
        \DB::statement("UPDATE opes SET people = jsonb_build_array() WHERE people = jsonb_build_array('')");

        // Venter litt med denne. Mangler UI-komponent
        // $this->changeColumnToJsonArray('opes_publications', 'corrections');

        Schema::table('opes_publications', function (Blueprint $table) {
            // 2.2
            $table->smallInteger('edition_nr')->unsigned()->default(1);

            $table->dateTime('deleted_at')->nullable();
        });

        Record::with('editions')->get()->each(function (Record $record) {
            $record->inv_no = str_replace('P.Oslo inv. ', '', $record->inv_no);

            foreach ($record->editions as $pub) {
                if (preg_match('/P\.Oslo (.*)/', $pub->ser_vol, $matches)) {
                    $record->p_oslo_vol = $matches[1];
                    $record->p_oslo_nr = $pub->pg_no;
                }

                // 1.7
                if ($pub->ddbdp_pmichcitation) {
                    $record->papyri_dclp_url = 'https://papyri.info/ddbdp/' . $pub->ddbdp_pmichcitation;
                }

                // 2.6
                if ($pub->sb) {
                    $pub->sb = str_replace('SB ', '', $pub->sb);
                    $pub->saveQuietly();
                }
            }

            // 1.9
            if ($record->notes_on_preservation) {
                $record->conservation_notes = $record->notes_on_preservation;
            }

            $record->saveQuietly();
        });

        Schema::table('opes', function (Blueprint $table) {
            // 1.9
            $table->dropColumn('notes_on_preservation');

            // 1.10
            $table->dropColumn('further_rep');

            // 1.11
            $table->dropColumn('further_replication_note');

            // 1.13
            $table->dropColumn('rep_ser_old');
            $table->dropColumn('rep_pg_no_old');
            $table->dropColumn('mounted');
            $table->dropColumn('extent');
            $table->dropColumn('institution');
            $table->dropColumn('status');
            $table->dropColumn('quote');
            $table->dropColumn('processing_number');
        });

        Schema::table('opes_publications', function (Blueprint $table) {
            // Make opes_id nullable, since we sometimes need to create new publications before the main record
            // has been created If editing is cancelled, we can get leftover publications with opes_id=NULL.
            // These can be safely deleted.
            $table->integer('opes_id')->unsigned()->nullable()->change();

            // 2.3
            $table->dropColumn('ddbdp_pmichcitation');
            $table->dropColumn('ddbdp_omichcitation');
            $table->dropColumn('ddbdp_p_rep');
            $table->dropColumn('ddbdp_o_rep');
        });

        $this->createView('opes_view', 2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('opes_view');

        // Wait with this since we don't have a UI element yet
        // $this->revertChangeColumnToJsonArray('opes', 'bibliography');

        $this->revertChangeColumnToJsonArray('opes', 'places');

        // Wait with this since we don't have a UI element yet
        // $this->revertChangeColumnToJsonArray('opes_publications', 'corrections');

        Schema::table('opes', function (Blueprint $table) {
            $table->dropColumn('p_oslo_vol');
            $table->dropColumn('p_oslo_nr');
            $table->dropColumn('internal_comments');
            $table->dropColumn('conservation_notes');
            $table->dropColumn('acquisition_year');
            $table->dropColumn('trismegistos_url');
            $table->dropColumn('papyri_dclp_url');

            // 4.6
            $table->renameColumn('subjects', 'subj_headings');

            // 4.7
            $table->renameColumn('people', 'persons');

            // 4.9
            $table->renameColumn('places', 'geographica');

            $table->text('notes_on_preservation')->nullable();
            $table->text('further_rep')->nullable();
            $table->text('further_replication_note')->nullable();

            $table->text('rep_ser_old')->nullable();
            $table->text('rep_pg_no_old')->nullable();
            $table->text('mounted')->nullable();
            $table->text('extent')->nullable();
            $table->text('institution')->nullable();
            $table->text('status')->nullable();
            $table->integer('quote')->unsigned()->nullable();
            $table->text('processing_number')->nullable();
        });

        Schema::table('opes_publications', function (Blueprint $table) {
            $table->dropColumn('edition_nr');
            $table->dropColumn('deleted_at');

            $table->text('ddbdp_pmichcitation')->nullable();
            $table->text('ddbdp_omichcitation')->nullable();
            $table->text('ddbdp_p_rep')->nullable();
            $table->text('ddbdp_o_rep')->nullable();
        });

        $this->createView('opes_view', 1);
    }

    private function changeColumnToJsonArray(string $table, string $field)
    {
        \DB::statement("
            ALTER TABLE {$table}
            ALTER COLUMN {$field} TYPE jsonb
            USING
            CASE WHEN {$field} IS NULL OR {$field} = '' THEN json_build_array()
              ELSE array_to_json(regexp_split_to_array({$field}, E'; ?'))
            END
        ");
    }

    private function revertChangeColumnToJsonArray(string $table, string $field)
    {
        \DB::statement("
            ALTER TABLE {$table}
            ALTER COLUMN {$field} TYPE text
            USING
            CASE
              WHEN jsonb_array_length({$field}) = 0 THEN NULL
              ELSE {$field}->>0
            END
        ");
    }
}
