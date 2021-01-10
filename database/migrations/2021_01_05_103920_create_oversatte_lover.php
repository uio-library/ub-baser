<?php

use App\Traits\MigrationHelper;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOversatteLover extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversatte_lover_lover', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date('dato');
            $table->integer('nummer')->unsigned();
            $table->enum('dok_type', ['lov', 'forskrift']);
            $table->text('tittel');
            $table->text('kort_tittel')->nullable();
            $table->text('note')->nullable();

            $table->unique(['dato', 'nummer', 'dok_type']);
        });

        Schema::create('oversatte_lover_oversettelser', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('lov_id')->unsigned();
            $table->text('sprak');
            $table->text('tittel');
            $table->text('kort_tittel')->nullable();
            $table->text('oversetter')->nullable();
            $table->text('bibsys')->nullable();
            $table->text('url')->nullable();
            $table->text('note')->nullable();
            $table->text('inote')->nullable();
            $table->text('utgave')->nullable();

            $table->foreign('lov_id')
                ->references('id')->on('oversatte_lover_lover');
        });

        $this->createView('oversatte_lover_view', 1);

        \DB::table('bases')->insert([
            [
                'id' => 'oversatte_lover',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'basepath' => 'oversatte-lover',
                'namespace' => 'Lover',
                'languages' => json_encode(['nb', 'en']),
                'default_language' => 'nb',
                'name' => json_encode([
                    'nb' => 'Oversatte lover',
                    'en' => 'Translated Norwegian Legislation',
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
        $this->dropView('oversatte_lover_view');

        Schema::dropIfExists('oversatte_lover_oversettelser');
        Schema::dropIfExists('oversatte_lover');

        \DB::table('bases')
            ->where(['id' => 'oversatte-lover'])
            ->delete();
    }
}
