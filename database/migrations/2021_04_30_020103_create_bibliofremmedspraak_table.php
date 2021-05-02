<?php

use App\Traits\MigrationHelper;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBibliofremmedspraakTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibliofremmedspraak', function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->text('forfatter')->nullable();
            $table->text('tittel')->nullable();
            $table->text('tidsskrift')->nullable();
            $table->text('antologi')->nullable();
            $table->text('angitti')->nullable();
            $table->text('utgivelsessted')->nullable();
            $table->text('nummer')->nullable();
            $table->text('utgivelsesaar')->nullable();
            $table->text('forlag')->nullable();
            $table->text('emne')->nullable();
            $table->text('type')->nullable();
            $table->text('dato')->nullable();
            $table->text('utgiver')->nullable();
            $table->text('ansvarsangivelse')->nullable();
            $table->text('eissn')->nullable();
            $table->text('issn')->nullable();
            $table->text('isbn')->nullable();
            $table->text('eisbn')->nullable();
            $table->text('boktittel')->nullable();
            $table->text('nettsted')->nullable();
            $table->text('spraak')->nullable();
            $table->text('url')->nullable();
            $table->text('sidetall')->nullable();
            $table->text('digitalisering')->nullable();

            $table->index('forfatter');
            $table->index('tittel');
        });

        \DB::table('bases')->insert([
            [
                'id' => 'bibliofremmedspraak',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'bibliofremmedspraak',
                'namespace' => 'Bibliofremmedspraak',
                'languages' => json_encode(['nb']),
                'default_language' => 'es',
                'name' => json_encode([
                    'nb' => 'Fremmedspråk og Fremmedspråkundervisnig. Fransk, spansk og tysk',
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
            ->where(['id' => 'bibliofremmedspraak'])
            ->delete();

        Schema::drop('bibliofremmedspraak');
    }
}
