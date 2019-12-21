<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKorrekturstatus extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            $table->tinyInteger('korrekturstatus')->default(1);
        });
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->createView('litteraturkritikk_records_search', 4);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        Schema::table('litteraturkritikk_records', function (Blueprint $table) {
            $table->dropColumn('korrekturstatus');
        });
        $this->createView('litteraturkritikk_records_search', 3);
    }
}
