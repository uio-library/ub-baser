<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordPersonConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->unique(['record_id', 'person_id', 'person_role']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->dropUnique('litteraturkritikk_record_person_record_id_person_id_person_role_unique');
        });
    }
}
