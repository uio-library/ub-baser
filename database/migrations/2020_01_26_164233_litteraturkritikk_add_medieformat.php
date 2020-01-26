<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LitteraturkritikkAddMedieformat extends Migration
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
            $table->text('medieformat')->nullable();
            $table->text('verk_originaltittel')->nullable();
            $table->text('verk_originalspraak')->nullable();
        });

        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->createView('litteraturkritikk_records_search', 6);
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
            $table->dropColumn('medieformat');
            $table->dropColumn('verk_originaltittel');
            $table->dropColumn('verk_originalspraak');
        });

        $this->createView('litteraturkritikk_records_search', 5);
    }
}
