<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LitteraturkritikkAllowMultipleRoles extends Migration
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
        $this->dropView('litteraturkritikk_personer_view');

        \DB::update("update litteraturkritikk_record_person set person_role = concat('[\"', person_role, '\"]') ");

        \DB::update("ALTER TABLE \"litteraturkritikk_record_person\" DROP CONSTRAINT IF EXISTS litteraturkritikk_record_person_record_id_person_id_person_role");

        \DB::update("ALTER TABLE \"litteraturkritikk_record_person\" ALTER COLUMN \"person_role\" TYPE jsonb USING person_role::jsonb");

        foreach (['verk_spraak', 'verk_originalspraak', 'spraak'] as $field) {
            \DB::update("update litteraturkritikk_records set $field = concat('[\"', $field, '\"]') ");
            \DB::update("update litteraturkritikk_records set $field = '[]' where $field = '[\"\"]'");

            \DB::update("ALTER TABLE \"litteraturkritikk_records\" ALTER COLUMN \"$field\" TYPE jsonb USING \"$field\"::jsonb");
        }

        $this->createView('litteraturkritikk_personer_view', 2);
        $this->createView('litteraturkritikk_records_search', 7);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropMaterializedView('litteraturkritikk_records_search');
        $this->dropView('litteraturkritikk_personer_view');

        \DB::update("ALTER TABLE \"litteraturkritikk_record_person\" ALTER COLUMN \"person_role\" TYPE text USING person_role::text");
        \DB::update("update litteraturkritikk_record_person set person_role = substring(person_role from 3 for length(person_role) - 4 ) ");

        foreach (['verk_spraak', 'verk_originalspraak', 'spraak'] as $field) {
            \DB::update("ALTER TABLE \"litteraturkritikk_records\" ALTER COLUMN \"$field\" TYPE text USING \"$field\"::text");
            \DB::update("update litteraturkritikk_records set $field = NULL where length($field) <= 2");
            \DB::update("update litteraturkritikk_records set $field = substring($field from 3 for length($field) - 4) where length($field) > 2");
        }

        $this->createView('litteraturkritikk_personer_view', 1);
        $this->createView('litteraturkritikk_records_search', 6);
    }
}
