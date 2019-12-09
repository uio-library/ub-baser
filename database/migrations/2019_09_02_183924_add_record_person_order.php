<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordPersonOrder extends Migration
{
    public function addPosisjonWhere($op, $value)
    {
        $recs = \DB::table('litteraturkritikk_record_person')
            ->where('person_role', $op, $value)
            ->orderBy('record_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $recordId = null;
        $counter = 1;
        foreach ($recs as $rec) {
            if ($recordId !== $rec->record_id) {
                $recordId = $rec->record_id;
                $counter = 1;
            }

            \DB::table('litteraturkritikk_record_person')
                ->where('id', '=', $rec->id)
                ->update(['posisjon' => $counter++]);
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->integer('posisjon')->nullable();
        });

        $this->addPosisjonWhere('=', 'kritiker');
        $this->addPosisjonWhere('!=', 'kritiker');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('litteraturkritikk_record_person', function (Blueprint $table) {
            $table->dropColumn('posisjon');
        });
    }
}
