<?php
use App\Bases\Opes\Record;
use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameConservationStatus extends Migration
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
            $table->renameColumn('conservation_status', 'state_of_preservation');
        });

        $this->createView('opes_view', 3);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('opes_view');

        Schema::table('opes', function (Blueprint $table) {
            $table->renameColumn('state_of_preservation', 'conservation_status');
        });

        $this->createView('opes_view', 3);
    }
}
