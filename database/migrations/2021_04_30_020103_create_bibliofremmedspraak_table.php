<?php

use App\Traits\MigrationHelper;
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
            $this->addCommonFields($table);$table->string('forfatter')->nullable();
            $table->string('forfatter')->nullable();
            $table->string('tittel')->nullable();
            $table->string('tidsskrift')->nullable();
            $table->string('antologi')->nullable();
            $table->string('angitti')->nullable();
            $table->string('utgivelsessted')->nullable();
            $table->string('utgivelsesår')->nullable();
            $table->string('forlag')->nullable();
            $table->string('emne')->nullable();
            $table->string('type')->nullable();
            $table->string('dato')->nullable();
            $table->string('utgiver')->nullable();
            $table->string('ansvarsangivelse')->nullable();
            $table->string('eissn')->nullable();
            $table->string('issn')->nullable();
            $table->string('isbn')->nullable();
            $table->string('eisbn')->nullable();
            $table->string('eissn')->nullable();
            $table->string('boktittel')->nullable();
            $table->string('nettsted')->nullable();
            $table->string('spraak')->nullable();
            $table->string('url')->nullable();
            $table->string('sidetall')->nullable();
            $table->string('digitalisering')->nullable();

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
                'languages' => json_encode(['nb', 'es', 'en']),
                'default_language' => 'es',
                'name' => json_encode([
                    'es' => 'Bibliografía espaniol',
                    'en' => 'Bibliography on foreign language',
                    'nb' => 'Bibliografi over fremmedspråk',
                ]),
                'class_bindings' => json_encode([
                    'RecordView' => 'Record',
                ], JSON_FORCE_OBJECT),
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
