<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bases', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->timestamps();
            $table->text('basepath');
            $table->json('languages');
            $table->text('default_language');
            $table->json('name');
            $table->string('namespace');
            $table->json('class_bindings');
        });

        \DB::table('bases')->insert([
            [
                'id' => 'bibsys',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'bibsys',
                'namespace' => 'Bibsys',
                'languages' => json_encode(['nb']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Bibsys katalogdump'
                ]),
                'class_bindings' => json_encode([
                    'AutocompleteService' => 'AutocompleteService',
                    'Record' => 'BibsysView',
                    'RecordView' => 'BibsysView',
                ], JSON_FORCE_OBJECT),
            ],
            [
                'id' => 'dommer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'dommer',
                'namespace' => 'Dommer',
                'languages' => json_encode(['nb']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Dommers populærnavn'
                ]),
                'class_bindings' => json_encode([
                    'AutocompleteService' => 'AutocompleteService',
                ], JSON_FORCE_OBJECT),
            ],
            [
                'id' => 'letras',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'letras',
                'namespace' => 'Letras',
                'languages' => json_encode(['nb', 'es']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Letras'
                ]),
                'class_bindings' => json_encode([
                    'RecordView' => 'Record',
                ], JSON_FORCE_OBJECT),
            ],
            [
                'id' => 'litteraturkritikk',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'norsk-litteraturkritikk',
                'namespace' => 'Litteraturkritikk',
                'languages' => json_encode(['nb']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Norsk litteraturkritikk'
                ]),
                'class_bindings' => json_encode([
                    'AutocompleteService' => 'AutocompleteService',
                ], JSON_FORCE_OBJECT),
            ],
            [
                'id' => 'opes',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'opes',
                'namespace' => 'Opes',
                'languages' => json_encode(['nb']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'OPES'
                ]),
                'class_bindings' => json_encode([
                ], JSON_FORCE_OBJECT),
            ],
        ]);

        Schema::table('pages', function (Blueprint $table) {
            $table->string('base_id')->nullable();
        });

        \DB::table('pages')
            ->update([
                'layout' => \DB::raw("concat(permission, '.layout')"),
            ]);

        \DB::table('pages')
            ->update([
                'base_id' => \DB::raw("permission"),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('base_id');
        });
        Schema::dropIfExists('bases');
    }
}
