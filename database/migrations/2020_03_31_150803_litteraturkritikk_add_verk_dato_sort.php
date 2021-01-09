<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;

class LitteraturkritikkAddVerkDatoSort extends Migration
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
        $this->createView('litteraturkritikk_records_search', 9);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->createView('litteraturkritikk_records_search', 8);
    }
}
