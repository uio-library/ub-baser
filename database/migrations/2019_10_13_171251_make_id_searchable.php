<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;

class MakeIdSearchable extends Migration
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
        $this->createView('litteraturkritikk_records_search', 3);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->createView('litteraturkritikk_records_search', 2);
    }
}
