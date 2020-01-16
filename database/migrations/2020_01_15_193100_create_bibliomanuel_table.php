<?php
use App\Traits\MigrationHelper;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBibliomanuelTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibliomanuel', function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->string('forfatter')->nullable();
            $table->string('tittel')->nullable();
            $table->string('antologi')->nullable();
            $table->string('boktittel')->nullable();
            $table->string('redaktorer')->nullable();
            $table->string('utgivelsessted')->nullable();
            $table->string('avis')->nullable();
            $table->string('tidsskriftstittel')->nullable();
            $table->string('nettsted')->nullable();
            $table->string('utgiver')->nullable();
            $table->string('aar')->nullable();
            $table->string('dato')->nullable();
            $table->string('type')->nullable();
            $table->string('bind')->nullable();
            $table->string('hefte')->nullable();
            $table->string('nummer')->nullable();
            $table->string('sidetall')->nullable();
            $table->string('isbn')->nullable();
            $table->string('issn')->nullable();
            $table->string('eissn')->nullable();
            $table->string('url')->nullable();


            $table->index('forfatter');
            $table->index('tittel');
        });

        \DB::table('bases')->insert([
            [
                'id' => 'bibliomanuel',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'bibliomanuel',
                'namespace' => 'Bibliomanuel',
                'languages' => json_encode(['nb', 'en']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Bibliografía sobre la obra de Manuel Vázquez Montalbán'
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
            ->where(['id' => 'bibliomanuel'])
            ->delete();

        Schema::drop('bibliomanuel');
    }
}
