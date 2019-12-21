<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;

class CreateDommerMaterializedView extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dropView('dommer_view');
        $this->createView('dommer_view', 2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('dommer_view');
        $this->createView('dommer_view', 1);
    }
}
