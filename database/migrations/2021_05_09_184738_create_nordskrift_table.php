<?php

use App\Traits\MigrationHelper;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNordskriftTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('nordskrift', function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->text('forfatter')->nullable();
            $table->text('tittel')->nullable();
            $table->text('sekundartittel')->nullable();
            $table->text('sider')->nullable();
            $table->text('nokkelord')->nullable();
            $table->text('dato')->nullable();
            $table->text('utgivelsessted')->nullable();
            $table->text('utgiver')->nullable();
            $table->text('isbn')->nullable();
            $table->text('abstrakt')->nullable();
            $table->text('notater')->nullable();
            $table->text('elektroniskressursnummer')->nullable();

            $table->index('forfatter');
            $table->index('tittel');
        });
        \DB::table('bases')->insert([
            [
                'id' => 'nordskrift',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'nordskrift',
                'namespace' => 'Nordskrift',
                'languages' => json_encode(['nb']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Nordisk skriftkultur bibliografi',
                ]),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('bases')
            ->where(['id' => 'nordskrift'])
            ->delete();

        Schema::drop('nordskrift');
    }
}
