<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OpesAddPublicColumn extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opes', function (Blueprint $table) {
            $table->boolean('public')->default(0);
        });

        \DB::table('opes')
            ->update(['public' => 1]);

        \DB::table('opes')
            ->whereIn('id', [30, 31, 32])
            ->update(['public' => 0]);

        $this->dropMaterializedView('opes_view');
        $this->createView('opes_view', 1);
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
            $table->dropColumn('public');
        });

        $this->createView('opes_view', 1);
    }
}
