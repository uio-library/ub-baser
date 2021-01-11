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
            $table->boolean('public')->default(false);
        });

        \DB::table('opes')
            ->update(['public' => true]);

        \DB::table('opes')
            ->whereIn('id', [30, 31, 32])
            ->update(['public' => false]);

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
